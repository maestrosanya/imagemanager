<?php

namespace ImageManager\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Имя таблицы
    protected $table = 'm_images';

    // Обратная связь к модели Folder "многие к одному"
    public function folder()
    {
        return $this->belongsTo('ImageManager\Model\Folder', 'id', 'folder_id');
    }
}
