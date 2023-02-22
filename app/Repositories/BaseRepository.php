<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Cache;

abstract class BaseRepository implements RepositoryInterface
{
    //model muốn tương tác
    protected $model;

    //khởi tạo
    public function __construct()
    {
        $this->setModel();
    }

    //lấy model tương ứng
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll()
    {
        $modelName = get_class($this->model);
        $cacheKey = $modelName."CacheAll";
        $data = Cache::get($cacheKey);
        if (!$data) {
            $data =  $this->model->all();
            Cache::remember($cacheKey, 5, function () use ($data) {
                return $data;
            });
        }
        return $data;
        //return $this->model->all();
    }

    public function find($id)
    {
        $modelName = get_class($this->model);
        $cacheKey = $modelName."ById".$id;
        $data = Cache::get($cacheKey);
        if (!$data) {
            $data =  $this->model->find($id);
            Cache::remember($cacheKey, 5, function () use ($data) {
                return $data;
            });
        }
        return $data;
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }
}
