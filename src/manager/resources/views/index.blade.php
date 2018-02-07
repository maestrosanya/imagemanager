@extends('admin::layouts.admin-panel')

@section('content')
<div class="imgr__wrapper">
    <div class="imgr__box">
        <div class="imgr__header">

            <div class="imgr__header__btn">
                <button id="imgr_btn_back"><span><i class="fa fa-level-up" aria-hidden="true"></i></span> Назад</button>
                <button id="imgr_btn_add_image" title="Добавить изображение"><span><i class="fa fa-picture-o" aria-hidden="true"></i></span> add image</button>
                <button id="imgr_btn_add_folder"><span><i class="fa fa-folder" aria-hidden="true"></i></span> add folder</button>
                <button id="imgr_btn_remove"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span> remove</button>
            </div>

            <div class="imgr__header__search">
                <div class="imgr__header__search__input">
                    <input type="text" id="imgr_search_folder_name" placeholder="Найти папку">
                    <button id="imgr_search_folder_btn">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

        </div>
        <div class="imgr__content">

            @if(isset($folders))
            <a href=""></a>
            @endif
        </div>
    </div>
</div>
@endsection


