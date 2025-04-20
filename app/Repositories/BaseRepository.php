<?php

namespace App\Repositories;

use App\Contracts\BaseContract;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseContract
{
    /**
     * model property on class instances
     *
     * @var Model
     */
    protected $model;

    /**
     * Constructor to bind model to repo
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * To Create a record
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }

    /**
     * To update a record
     * 
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id): mixed 
    {
        return $this->find($id)->update($attributes);
    }

    /**
     * To get a record with id
     *
     * @param integer $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * To get a record with given data
     * 
     * @param array $attributes
     * @return mixed
     */
    public function findOneBy(array $data): mixed
    {
        return $this->model->where($data)->first();
    }

    /**
     * To get list of records
     * 
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): mixed
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * To get list of records with given data
     * 
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data): mixed
    {
        return $this->model->where($data)->get();
    }

    /**
     * Remove a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool
    {
        return $this->model->find($id)->delete();
    }
}