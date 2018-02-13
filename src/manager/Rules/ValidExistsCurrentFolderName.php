<?php

namespace ImageManager\Rules;

use Illuminate\Contracts\Validation\Rule;
use ImageManager\Model\Folder;

class ValidExistsCurrentFolderName implements Rule
{
    public $parent_folder;

    public $message = 'Папка с таким именем уже существует';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parent_folder)
    {
        $this->parent_folder = $parent_folder;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $folder = new Folder();

        $result = $folder->where('parent_folder', $this->parent_folder)->where('name', $value)->get();

        if ($result->isEmpty()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
