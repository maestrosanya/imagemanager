<?php

namespace ImageManager\Http\Controllers;

use App\Http\Controllers\Controller;
use ImageManager\Model\Folder;
use ImageManager\Model\Image;
use ImageManager\Repository\FoldersRepository;
use ImageManager\Repository\ImagesRepository;

class MainController extends Controller
{
    protected $folder_rep;
    protected $image_rep;

    public function __construct()
    {
        $this->folder_rep = new FoldersRepository(new Folder());
        $this->image_rep = new ImagesRepository(new Image());
    }

    public function index()
    {
        dump($this->folder_rep->getAll());
    }

}