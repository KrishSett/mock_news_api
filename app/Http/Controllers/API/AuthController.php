<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Middleware\HashVerify;
use App\Models\API\HeaderHash;
use App\Services\API\GuestTokenService;
use App\Services\API\HeaderHashService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class AuthController extends ApiBaseController
{
    protected $guestTokenService;
    protected $headerHashService;

    public function __construct(GuestTokenService $guestTokenService, HeaderHashService $headerHashService)
    {
        parent::__construct();
        $this->guestTokenService = $guestTokenService;
        $this->headerHashService = $headerHashService;
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "token"    => ["required"]
        ]);

        if ($validate->fails()) {
            return $this->responseError($validate->errors()->first(), 422);
        }

        $authData = decryptAuthHash($request->token);

        if (empty($authData)) {
            return $this->responseError('Access prohibited',403);
        }

        $email = $authData['email'];
        $password = $authData['password'];

        $user = User::where("email", $email)->first();
        if (Hash::check($password, $user->password)) {
            auth()->login($user);
            // Revoke existing tokens for the user (optional, but good practice for single-device logins)
            $user->tokens()->delete();
            $token = auth()->user()->createToken("auth_token", ["*"], now()->addDay());
            return $this->responseSuccess(['success' => true, 'token' => $token->plainTextToken, 'time' => currentMillisecond()], 201);
        }

        return $this->responseError('Auth failed',401);
    }

    public function guestHash(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'visitorId' => ['required', 'alpha-num']
        ]);

        if ($validated->fails()) {
            return $this->responseError($validated->errors()->first(), 422);
        }

        $token = $this->guestTokenService->fetchGuestToken($request->visitorId);
        if (empty($token)) {
            $hashes = $this->headerHashService->fetchList();

            if (empty($hashes)) {
                return $this->responseError("No hashes found", 422);
            }

            $tokenData = [
                'user'   => $request->visitorId,
                'hashes' => $hashes,
                'time'   => time()
            ];

            $token = encryptAuthToken($tokenData);
            $created =  $this->guestTokenService->createToken([
                $token,
                $request->visitorId 
            ]);

            if (!$created) {
                return $this->responseError("Something went wrong", 500);
            }
        }

        return $this->responseSuccess([
            '_tkn' => $token
        ]);
    }

    public function logout(Request $request)
    {
        request()->user()->currentAccessToken()->delete();
        return $this->responseSuccess(['message' => 'Successfully logged out']);
    }
}
