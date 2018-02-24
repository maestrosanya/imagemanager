<?php

namespace ImageManager\Repository;

use Illuminate\Support\Facades\Validator;
use ImageManager\Model\Folder;

class FoldersRepository extends Repository
{
    public function __construct(Folder $model)
    {
        parent::__construct($model);
    }

    public function getFoldersWhere($str1, $str2)
    {
        return $this->model->where($str1, $str2)->get();
    }

    // получить весь родительский уровень
    public function getAllParentLevel($id)
    {
        if ($id == 0) {
            $parent = $this->model->find(1);
        }
        else {
            $parent = $this->model->find($id);
        }

        return $parent->parent_folder;
    }

    public function getFolder($id_folder)
    {
        if ($id_folder == 0) {
            return null;
        }

        return $this->model->find($id_folder);
    }

    public function getImagesFromFolder($id_folder)
    {
        if ($id_folder == 0) {
            return 'parent';
        }
        else {
            return $this->model->find($id_folder)->images()->get();
        }
    }

    // Создаёт новую папку
    public function createNewFolder($parent_folder, $name_folder)
    {

        $folder = new $this->model;

        $folder->name           = $name_folder;
        $folder->parent_folder  = $parent_folder;

        $folder->save();
    }

    public function existsFolderName($parent_folder, $name_folder)
    {
        $folder = $this->model->where('parent_folder', $parent_folder)->where('name', $name_folder)->get();

        return $folder;
    }

    public function updateFolder($newName, $folderId)
    {
        $folder = Folder::find($folderId);

        $folder->name = $newName;

        $folder->save();
    }
}