<?php


namespace App\Services\API;


use App\Contracts\API\LatestContentContract;

class LatestContentService
{
    /**
     * @var LatestContentContract
     */
    protected $latestContentRepository;

    /**
     * LatestContentService constructor.
     *
     * @param LatestContentContract $latestContentRepository
     */
    public function __construct(LatestContentContract $latestContentRepository)
    {
        $this->latestContentRepository = $latestContentRepository;
    }

    /**
     * Create latest contents.
     *
     * @param array $attr
     */
    public function createLatestContent(array $attr)
    {
        return $this->latestContentRepository->createLatestContent($attr);
    }
}
