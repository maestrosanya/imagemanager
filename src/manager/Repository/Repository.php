<?php

namespace ImageManager\Repository;

abstract class Repository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }
}