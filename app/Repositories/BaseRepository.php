<?php

namespace App\Repositories;

use App\Exceptions\RepositoryInternalException;
use phpDocumentor\Reflection\Types\Mixed_;

class BaseRepository
{
    protected $model;
    protected $table;

    public function getModel()
    {
        if (!$this->model) {
            throw new RepositoryInternalException("Undefined model!");
        }

        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = new $model;
    }

    /**
     * @return mixed
     * @throws RepositoryInternalException
     */
    public function getTable()
    {
        if (!$this->table) {
            throw new RepositoryInternalException("Undefined table!");
        }

        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->select($columns)->get();
    }

    public function find(int $id, array $columns = ['*'])
    {
        return $this->model->select($columns)
            ->where($this->model->getKeyName(), $id)
            ->first();
    }

    public function findBy(string $column, Mixed_ $value, array $columns = ['*'])
    {
        return $this->model->select($columns)
            ->where($column, $value)
            ->first();
    }

    public function findAllBy(string $column, Mixed_ $value, array $columns = ['*'])
    {
        return $this->model->select($columns)
            ->where($column, $value)
            ->get();
    }

    public function create(array $params)
    {
       return $this->model->create($params);
    }

    public function update(int $id, array $params)
    {
        return $this->model
            ->where('id', $id)
            ->create($params);
    }
}
