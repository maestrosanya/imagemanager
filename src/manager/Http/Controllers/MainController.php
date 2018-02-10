<?php

namespace ImageManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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

    public function index(Request $request, $id_folder = 0)
    {
      //  dump($this->folder_rep->getFoldersWhere('parent_folder', '0'));

       // dump($id_folder);


        if (isset($id_folder)) {
            $folders = $this->folder_rep->getFoldersWhere('parent_folder', $id_folder);

            $request->flashOnly('id_folder');

           // Session::flash('id_folder', $id_folder);

          //  dump(Session::all());

          //  $previousUrl = isset(Session::get('_previous')) : Session::get('_previous') ?
        }
        else {
            $folders = $this->folder_rep->getFoldersWhere('parent_folder', '0');
        }


       // return view('imagemanager::index', ['folders' => $folders, 'btn_back' => ''])->render();

       // $dataAjax['manager'] = view('imagemanager::index')->render();
       // return 'hello';

       // return response()->view('imagemanager::index');



        $content = view('imagemanager::imgr-content', ['folders' => $folders])->render();

        $data = [];
        $data = array_add($data, 'btn_back', '');
        $data = array_add($data, 'content', $content);
        //$data = array_add($data, 'folders', $folders);


        $manager = view('imagemanager::index')->with($data)->render();
        return response()->json(array('manager'=> $manager), 200);
    }

    public function content(Request $request, $data = [])
    {
        $parent_level = 0;
        $images = [];
        $currentFolderName = 'parent';


        if (isset($request->id_folder)) {
            $folders = $this->folder_rep->getFoldersWhere('parent_folder', $request->id_folder);

            $currentFolder = $this->folder_rep->getFolder($request->id_folder);

            if ($currentFolder) {
                $currentFolderName = $currentFolder->name;
            }

            $images = $this->folder_rep->getImagesFromFolder($request->id_folder);

        }
        else {
            $folders = $this->folder_rep->getFoldersWhere('parent_folder', '0');
        }

        if (isset($request->parent_folder)) {
            $parent_level = $this->folder_rep->getAllParentLevel($request->parent_folder);
        }


        $data = array_add($data, 'folders', $folders);
        $data = array_add($data, 'back', $parent_level);
        $data = array_add($data, 'images', $images);
        $data = array_add($data, 'currentFolder', $currentFolderName);

        $content = view('imagemanager::imgr-content')->with($data)->render();

        return response()->json(array('content'=> $content), 200);
    }


    /**
     * return html
     */
    // Ожидает GET/POST параметры: id_folder, parent_folder, new_name_folder - имя новой папки
    public function addFolder(Request $request)
    {
        $data = [];

        if (isset($request->new_name_folder)) {

            /*$validator = $this->validate($request, [
                'new_name_folder' => 'required|min:1|max:3'
            ]);*/

            $validator = Validator::make(
                array('new_name_folder' => $request->new_name_folder),
                array('new_name_folder' => array('required', 'min:5'))
            );

            if ($validator->passes()) {
                $this->folder_rep->createNewFolder($request->parent_folder, $request->new_name_folder);
            }
            else {
                $messages = $validator->messages();

                $data = array_add($data, 'errors', $messages);  
            }


           // $this->folder_rep->createNewFolder($request->parent_folder, $request->new_name_folder);
        }

        return $this->content($request, $data);
    }


}