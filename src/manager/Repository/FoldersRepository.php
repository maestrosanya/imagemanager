<?php

namespace ImageManager\Repository;

use ImageManager\Model\Folder;

class FoldersRepository extends Repository
{
    public function __construct(Folder $model)
    {
        parent::__construct($model);
    }

    public function getFoldersWhere($str1, $str2)
    {
        return $this->model->where('parent_folder', '0')->get();
    }
}