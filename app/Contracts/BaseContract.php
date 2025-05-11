<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface BaseContract
{
    /**
     * Create a model instance
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;

    /**
     * Update a model instance
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id): mixed;

    /**
     * Find one by ID
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;

    /**
     * Find one by given data
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data): mixed;

    /**
     * Return all model rows
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'desc'): mixed;

    /**
     * Find records by given data
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data): mixed;

    /**
     * Delete one by Id
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get with relations
     * @param array $relations
     * @return Builder
     */
    public function with(array $relations): Builder;
}
