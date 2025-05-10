<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestToken extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "guest_tokens";

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "token",
        "visitor_id",
        "ip_address",
        "expires_at"
    ];

    public function checkHash($visitorId, $hash)
    {
        $exist = GuestToken::where('visitor_id', $visitorId)
            ->select("token")
            ->latest()
            ->first();

        if (empty($exist)) {
            return false;
        }

        return $exist->token === $hash;
    }
}
