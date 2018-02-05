<?php

namespace ImageManager\Repository;

use ImageManager\Model\Image;

class ImagesRepository extends Repository
{
    public function __construct(Image $model)
    {
        parent::__construct($model);
    }
}