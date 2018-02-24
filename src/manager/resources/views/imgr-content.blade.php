@if(isset($errors) and !empty($errors->first()))
<div>
    <div class="imgr__errors alert alert-danger" role="alert">{{ $errors->first() }}</div>

    {{--@foreach($errors->all() as $error)
        <div class="imgr__errors alert alert-danger" role="alert">{{ $error }}</div>
    @endforeach--}}
</div>
@endif

<div class="imgr__content" id="imgr__content">
    <input type="text" id="imgr_parent_folder" value="{{ isset($back) ? $back : '0' }}" hidden>
        <div class="row">
            <div class="col-sm-3 imgr__content__folders">
                @if(isset($currentFolder) and $currentFolder != 'parent')
                    <div>
                        <div><i class="fa fa-folder-open-o" aria-hidden="true"></i> {{ $currentFolder }}</div>
                    </div>
                @endif
                @if(isset($folders))
                    <ul class="list-inline">
                        @foreach($folders as $folder)
                            <li class="imgr__content__folders__item">
                                <input type="checkbox" name="imgr_checked_folder" value="{{ $folder->id }}" hidden>
                                <i class="fa fa-folder-o" aria-hidden="true"></i>
                                <div class="imgr_folder_btn" data-folder_id="{{ $folder->id }}" data-parent_folder="{{ $folder->parent_folder }}">{{ $folder->name }}</div>

                                <div class='imgr_folder_btn_options'>
                                    <button class='imgr_folder_btn_options_rename' title="Переименовать"><i class='fa fa-pencil' aria-hidden='true'></i></button>
                                    <button class='imgr_folder_btn_options_remove' title="Удалить"><i class='fa fa-times' aria-hidden='true'></i></button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="col-sm-9 imgr__content__images">
                <div class="row">
                    @if(isset($images) and $images != 'parent')
                        @foreach($images as $image)
                            <div class="col-3 col-sm-4 col-md-3 col-lg-3 imgr__content__images__image">
                                <img src="{{ asset('storage/uploads') .'/'.  $image->file_name}}"  alt="{{ $image->original_name }}" width="100%" height="auto">
                                <div>
                                    {{ $image->original_name }}
                                </div>
                                <input type="checkbox" name="imgr_checked_images[]" value="{{ $image->file_name }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="imgr_folder_options_window rename">
                <div class="imgr_folder_options_window_content form-group">
                    <label for="imgr_rename_folder">Новое имя</label>
                    <input type="text" id="imgr_rename_folder" name="imgr_rename_folder">
                    <button id="imgr_rename_folder_btn">Переименовать</button>
                    <button id="imgr_rename_folder_btn_close">Отмена</button>
                </div>
            </div>

            <div class="imgr_folder_options_window remove">
                <div class="imgr_folder_options_window_content remove form-group">
                    <div class="imgr_folder_options_window_remove_info alert alert-danger" role="alert">
                        Внимание!!!
                    </div>

                    <button id="imgr_remove_folder_btn_close">Отмена</button>
                    <button id="imgr_remove_folder_btn">Удалить</button>

                </div>
            </div>

        </div>

</div>

<script>

    /*
    *  Всплывающие опции   // начало
    */

    // Всплывающие кнопки (Переименовать, Удалить)
    $('.imgr__content__folders__item').hover(function (e) {
        $(this).children('.imgr_folder_btn_options').css('display', 'block');
    }, function () {
        $(this).children('.imgr_folder_btn_options').css('display', 'none');
    });

    // Всплывающая опция "Переименовать"
    $('.imgr_folder_btn_options_rename').click(function (e) {
        e.preventDefault();

        $('.imgr_folder_options_window.rename').css('display', 'block');

        var currentFolderElement = $(this).parents('.imgr__content__folders__item').find('.imgr_folder_btn');

        var currentFolderName = $(currentFolderElement).text();
        var currentFolderId = $(currentFolderElement).attr('data-folder_id');

        $('#imgr_rename_folder').val(currentFolderName);
        $('#imgr_rename_folder').attr('data-imgr_rename_folder_id', currentFolderId);

    });

    $('#imgr_rename_folder_btn_close').click(function () {
        $('.imgr_folder_options_window').css('display', 'none');
    });

    // Всплывающая опция "Переименовать". Ajax отправка данных
    $('#imgr_rename_folder_btn').click(function (e) {
        e.preventDefault();

        var newNameFolder = $('#imgr_rename_folder').val();
        var folderId = $('#imgr_rename_folder').attr('data-imgr_rename_folder_id');

        $.ajax({
            type: 'POST',
            url: '/admin/image-manager/rename-folder',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'id_folder': sessionStorage.getItem('id_folder'),
                'parent_folder': sessionStorage.getItem('id_folder'),
                'new_name_folder': newNameFolder,
                'folderId': folderId
            },
            success: function (response) {
                $('#imgr__content').html(response.content);
            }
        });

    });

    // Всплывающая опция "Удалить папку"
    $('.imgr_folder_btn_options_remove').click(function (e) {
        e.preventDefault();

        $('.imgr_folder_options_window.remove').css('display', 'block');

        var currentFolderElement = $(this).parents('.imgr__content__folders__item').find('.imgr_folder_btn');

        var currentFolderName = $(currentFolderElement).text();
        var currentFolderId = $(currentFolderElement).attr('data-folder_id');

        $('#imgr_rename_folder').val(currentFolderName);
        $('#imgr_rename_folder').attr('data-imgr_rename_folder_id', currentFolderId);

        $('.imgr_folder_options_window_remove_info').html('<h3>ВНИМАНИЕ!</h3> Вы пытаетесь удалить папку "'+ currentFolderName + '". Находящиеся в папке данные будут удалены безвозвратно!');

    });

    $('#imgr_remove_folder_btn_close').click(function () {
        $('.imgr_folder_options_window').css('display', 'none');
    });


    /*
     *  Всплывающие опции   // конец
     */



    /*
    *   Ajax навигация по папкам
    */
    $('.imgr_folder_btn').click(function (e) {

        e.preventDefault();

        var id_folder       = $(this).attr('data-folder_id');
        var parent_folder   = $(this).attr('data-folder_id');

        $.ajax({
            type: 'POST',
            url: '/admin/image-manager/folder',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'id_folder': id_folder,
                'parent_folder': parent_folder
            },
            beforeSend: function () {
                sessionStorage.setItem('id_folder', id_folder);
            },
            success: function (data) {

                $('#imgr__content').html(data.content);
                console.log(data.content);

                console.log($(this).attr('data-parent_folder'));

                console.log(sessionStorage.getItem('id_folder'));

            }
        });

    });




</script>
