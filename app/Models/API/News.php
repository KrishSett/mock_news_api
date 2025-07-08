<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Route;

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

    /**
     * Accessor for short_description attribute
     *
     * @return Attribute
     */
    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!in_array(Route::currentRouteName(), ['news.details']) && !empty($value) && strlen($value) > config('homecontents.short_description_truncate_length', 50)) {
                    $length = (int) (config('homecontents.short_description_truncate_length', 50) - 3);
                    return substr($value, 0, $length) . '...';
                }

                return $value;
            },
        );
    }

    /**
     * Accessor for thumbnail attribute
     *
     * @return Attribute
     */
    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (empty($value) || $value === '') {
                    $fileName = "news-default-image.png";
                } else {
                    $fileName = "img/" . $value;
                }



                return getLazyLoadImageData($fileName, $attributes['uuid']);
            },
        );
    }

    /**
     * Foreign key relation with SubCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    /**
     * One to many relation with Tags
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tags::class,'news_tags','news_id','tags_id')->withPivot('active');
    }

    /**
     * One to one relation with LatestContent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestContent()
    {
        return $this->hasOne(LatestContent::class);
    }
}
