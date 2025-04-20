<?php

namespace App\Services\API;

use App\Contracts\API\SubcategoryContract;

class SubcategoryService
{
    /**
     * @var SubcategoryContract
     */
    protected $subCategoryRepository;

    /**
     * Instance of the class
     * 
     * @param \App\Contracts\API\SubcategoryContract $subCategoryRepository
     */
    public function __construct(SubcategoryContract $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function fetchSubcategoryDetailsBySlug(string $slug): mixed
    {
        $subcategory = $this->subCategoryRepository->fetchSubcategoryDetailsBySlug($slug);

        if (empty($subcategory)) {
            return [];
        }

        $news = $subcategory->news()
            ->select("news.id", "news.uuid", "news.title", "news.short_desctiprion", "news.thumbnail")
            ->with(['tags' => function ($query) {
                $query->select("tags.id", "tags.slug")->wherePivot('active', 1);
            }])
            ->orderBy('created_at','desc')
            ->paginate(5);

        $news->getCollection()->transform(function ($item) {
            $tags = [];

            foreach ($item->tags as $tag) {
                $tags[] = $tag->slug; // or use '' if you want empty strings as values
            }

            // Flatten tags and remove original tags relationship
            unset($item->tags);
            $item->tags = $tags;

            return $item;
        });

        return $news->toArray();
    }
}
