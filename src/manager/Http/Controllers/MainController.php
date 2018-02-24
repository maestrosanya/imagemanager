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
use ImageManager\Rules\ValidExistsCurrentFolderName;

class MainController extends Controller
{
    protected $folder_rep;
    protected $image_rep;

    public function __construct()
    {
        $this->folder_rep = new FoldersRepository(new Folder());
        $this->image_rep = new ImagesRepository(new Image());
    }

    public function index(Request $request)
    {
        $id_folder = 0;

        if (isset($id_folder)) {
            $folders = $this->folder_rep->getFoldersWhere('parent_folder', $id_folder);

            $request->flashOnly('id_folder');

        }
        else {
            $folders = $this->folder_rep->getFoldersWhere('parent_folder', '0');
        }

        $content = view('imagemanager::imgr-content', ['folders' => $folders])->render();

        $data = [];
        $data = array_add($data, 'btn_back', '');
        $data = array_add($data, 'content', $content);

        $manager = view('imagemanager::index')->with($data)->render();
        return response()->json(array('manager'=> $manager), 200);
    }

    /**
     * Генерирует контент приложения.
     *
     *  1) Ожидает POST параметры:
     *  $request->id_folder - id папки,
     *  $request->parent_folder - id родительской папки
     *
     *  2) $data - массив с переменными для вывода их в шаблон
     */
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
     * Ожидает POST параметры:
     *  $request->id_folder - id папки,
     *  $request->parent_folder - id родительской папки,
     *  $request->new_name_folder - имя новой папки
     */
    public function addFolder(Request $request)
    {
        $data = $this->validAddFolder($request);

        return $this->content($request, $data);
    }

    /**
     * Проверяет и создаёт папку в таблице m_folder
     * Возвращает массив с ошибками валидации
     *
     * @return array
     */
    private function validAddFolder(Request $request)
    {
        $data = [];

        if (isset($request->new_name_folder)) {

            $input = array('new_name_folder' => $request->new_name_folder);
            $rules = array('new_name_folder' => array('required', 'alpha_dash', 'min:1', 'max:25', new ValidExistsCurrentFolderName($request->parent_folder) ));
            $messages = array(
                'required' => 'Поле должно быть заполнено.',
                'min' => 'Имя папки должно быть не менее 1 символа.',
                'max' => 'Имя папки не должно превышать 25 символов.',
                'alpha_dash' => 'Имя папки может содержать только алфавитные символы, цифры, знаки подчёркивания (_) и дефисы (-)',
            );

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->passes()) {
                $this->folder_rep->createNewFolder($request->parent_folder, $request->new_name_folder);
            }
            else {
                $messages = $validator->messages();
                $data = array_add($data, 'errors', $messages);
            }
        }

        return $data;
    }

    /**
     * Ожидает POST параметры:
     *  $request->id_folder - id папки,
     *  $request->parent_folder - id родительской папки,
     *  $request->new_name_folder - имя новой папки
     */
    public function renameFolder(Request $request)
    {
        $data = $this->validRenameFolder($request);

        return $this->content($request, $data);
    }

    /**
     * Проверяет и переименовывает папку в таблице m_folder
     * Возвращает массив с ошибками валидации
     *
     * @return array
     */
    private function validRenameFolder(Request $request)
    {
        $data = [];

        if (isset($request->new_name_folder)) {

            $input = array('new_name_folder' => $request->new_name_folder);
            $rules = array('new_name_folder' => array('required', 'alpha_dash', 'min:1', 'max:25', new ValidExistsCurrentFolderName($request->parent_folder) ));
            $messages = array(
                'required' => 'Поле должно быть заполнено.',
                'min' => 'Имя папки должно быть не менее 1 символа.',
                'max' => 'Имя папки не должно превышать 25 символов.',
                'alpha_dash' => 'Имя папки может содержать только алфавитные символы, цифры, знаки подчёркивания (_) и дефисы (-)',
            );

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->passes()) {
                $this->folder_rep->updateFolder($request->new_name_folder, $request->folderId);
            }
            else {
                $messages = $validator->messages();
                $data = array_add($data, 'errors', $messages);
            }
        }

        return $data;
    }




    public function addImage(Request $request)
    {

        $filesArray = [];
        $res = '';
        $valid = '';

        for ($key = 0; $key < count($_FILES['uploads_new_images']['tmp_name']); $key++) {

            $filesArray[] = [
                'name' => $_FILES['uploads_new_images']['name'][$key],
                'tmp_name' => $_FILES['uploads_new_images']['tmp_name'][$key],
                'size' => $_FILES['uploads_new_images']['size'][$key],
                'type' => $_FILES['uploads_new_images']['type'][$key],
                'error' => $_FILES['uploads_new_images']['error'][$key]
            ];

        }


        $validator = Validator::make(
            array('file' => $request->file('uploads_new_images')),
            array('file.*' => 'image|mimes:jpeg,png,gif|max:10')
            );

        if ($validator->passes()) {
            $valid = 'Very good';
        }
        else {
            $messages = $validator->messages();
            $valid = $messages;
        }


        foreach ($filesArray as $file) {
            if (copy($file['tmp_name'], storage_path() .'/'. $file['name'])) {
                $res = 'Файл загружен на сервер';
            }
            else {
                $res = 'Ошибка при загрузке файла';
            }
        }


        return response()->json(array('content' => $filesArray, 'res' => $res, 'valid' => $valid), 200);
    }


}