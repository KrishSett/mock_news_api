<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

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
        "uuid",
        "title",
        "short_description",
        "description",
        "thumbnail",
        "subcategory_id",
        "active"
    ];

    protected $hidden = [
        "id",
        "pivot"
    ];

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class,'news_tags','news_id','tags_id')->withPivot('active');
    }

    public function latestContent()
    {
        return $this->hasOne(LatestContent::class);
    }
}
