<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\API\SubCategory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use SoftDeletes, HasSlug;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
        "name",
        "slug",
        "active",
        "list_order"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        "id"
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * One to many relation with SubCategory
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategories(array $options = [])
    {
        $query = $this->hasMany(SubCategory::class, 'category_id', 'id');

        if ($options['active'] ?? false) {
            $query->where('active', true);
        }

        if ($options['order_by'] ?? false) {
            $query->orderBy($options['order_by'], $options['sort'] ?? 'asc');
        }

        if ($options['limit'] ?? false) {
            $query->take($options['limit']);
        }

        return $query;
    }

    /**
     * Filter active category
     *
     * @return Category|null
     */
    public function activeCategories(): Category | null
    {
        return $this->active ? $this : null;
    }
}
