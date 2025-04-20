<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderHash extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "header_hashes";

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
        "route_prefix",
        "hash"
    ];

    public function checkHash($type, $hash)
    {
        $exist = HeaderHash::where('hash', $hash)
            ->where(function ($query) use ($type) {
                return $query->where('route_prefix', $type);
            })
            ->first();

        return ($exist && !empty($exist->toArray()));
    }

    public function getHash($type)
    {

        $headerHash = HeaderHash::where('route_prefix', $type)
            ->select('hash')
            ->first();
        
        if ($headerHash && !empty($headerHash->toArray())) {
            $hashAttr = [
                $headerHash?->hash,
                sha1(getenv('HASH_KEY_SALT') . time()),
                \Carbon\Carbon::now('UTC')->addDay()->timestamp,
                uniqid(),
            ];

            return base64_encode(implode('|', $hashAttr));
        }

        return null;
    }
}
