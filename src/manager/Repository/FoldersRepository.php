<?php

namespace ImageManager\Repository;

use ImageManager\Model\Folder;

class FoldersRepository extends Repository
{
    public function __construct(Folder $model)
    {
        parent::__construct($model);
    }
}