<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Middleware\HashVerify;
use App\Models\API\HeaderHash;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class AuthController extends ApiBaseController
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);

        if ($validate->fails()) {
            return $this->responseError($validate->errors()->first(), 422);
        }

        $user = User::where("email", $request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            auth()->login($user);
            // Revoke existing tokens for the user (optional, but good practice for single-device logins)
            $user->tokens()->delete();
            $token = auth()->user()->createToken("auth_token", ["*"], now()->addDay());
            return $this->responseSuccess(['success' => true, 'token' => $token->plainTextToken], 201);
        }

        return $this->responseError('Auth failed',401);
    }

    public function getHashes(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'type' => ['required', 'exists:header_hashes,route_prefix']
        ]);

        if ($validated->fails()) {
            return $this->responseError($validated->errors()->first(), 422);
        }

        $token = (new HeaderHash())->getHash($request->type);

        if (!empty($token)) {
            return $this->responseSuccess(['success'=> true,'token'=> $token],200);
        }

        return $this->responseError('No token.',422);
    }

    public function logout(Request $request)
    {
        request()->user()->currentAccessToken()->delete();
        return $this->responseSuccess(['message' => 'Successfully logged out']);
    }
}
