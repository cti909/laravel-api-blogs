<?php

namespace App\Repositories;

abstract class BaseRepository implements IBaseRepository
{
    protected $_model;

    public function __construct()
    {
        $this->setModel();
    }

    protected function setModel()
    {
        $this->_model = app()->make($this->getModel());
    }

    abstract function getModel();

    function create(mixed $data)
    {
        $instance = $this->_model::create($data);
        return $instance;
    }
    function update(mixed $data, mixed $id)
    {
        $instance = $this->_model::find($id);
        $instance->update($data);
        return $instance;
    }
    function destroy(mixed $id)
    {
        $instance = $this->_model::find($id);
        $instance->delete();
        return $instance;
    }
    function findAll(mixed $options)
    {
        // dd($options);
        return $this->_model::where($options['where'])
            ->with($options['relations'])
            ->orderBy($options['column'], $options['orderBy'])
            // ->paginate($options['limit'], ['*'], 'page', $options['page']);
            ->paginate($options['limit']);
    }
    function findOne(mixed $options)
    {
        $model = $this->_model::where($options['where'])->first(); // Lấy đối tượng mô hình thay vì đối tượng Builder
        if ($model) {
            $model->loadMissing($options['relations']); // Gọi phương thức loadMissing() trên đối tượng mô hình
            return $model;
        } else {
            return null;
        }
        // return $this->_model::where($options['where'])
        //     ->loadMissing($options['relations']);
    }
    function findById(mixed $id)
    {
        return $this->_model::find($id);
    }
}
