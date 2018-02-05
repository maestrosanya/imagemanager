<?php

namespace ImageManager\Model;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'm_folders';

    // Связь к модели Image "один ко многим"
    public function images()
    {
       return $this->hasMany('ImageManager\Model\Image', 'folder_id', 'id');
    }
}
