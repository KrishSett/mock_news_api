<?php

namespace App\Contracts\API;

/**
 * Interface HeaderHashContract
 *
 * @package App\Contracts\API\HeaderHashContract
 */
interface HeaderHashContract
{
    /**
     * @return array
     */
    public function list(): array;
}
