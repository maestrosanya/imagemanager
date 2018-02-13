
<div class="imgr__wrapper">
    <div class="imgr__box">
        <div class="imgr__header">

            <div class="imgr__header__btn">
                <button id="imgr_btn_back"><span><i class="fa fa-level-up" aria-hidden="true"></i></span> Назад</button>
                <button id="imgr_btn_add_image" title="Добавить изображение"><span><i class="fa fa-picture-o" aria-hidden="true"></i></span> add image</button>
                <button id="imgr_btn_show_folder_input"><span><i class="fa fa-folder" aria-hidden="true"></i></span> add folder</button>
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

            <div class="imgr__header__input">
                <div class="form-group">
                    <input type="text" id="new_name_folder" class="new_name_folder" name="new_name_folder">
                    <button id="imgr_btn_add_folder">Создать</button>
                </div>
            </div>

        </div>
        <div class="imgr__content" id="imgr__content">

            @if(isset($content))
                {!! $content !!}
            @endif
        </div>
    </div>
</div>

<script>

    $('#imgr_btn_back').click(function (e) {

        e.preventDefault();

        var id_folder       = $('#imgr_parent_folder').attr('value');
        var parent_folder   = $('#imgr_parent_folder').attr('value');

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

                console.log(sessionStorage.getItem('id_folder'));

            }
        });

    });


    /* Добавляет новую папку */
    $('.imgr__header__input').hide();

    $('#imgr_btn_show_folder_input').click(function (e) {
        e.preventDefault();

        $('.imgr__header__input').toggle(500);
    });

    $('#imgr_btn_add_folder').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/admin/image-manager/add-folder',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'id_folder': sessionStorage.getItem('id_folder'),
                'parent_folder': sessionStorage.getItem('id_folder'),
                'new_name_folder': $('#new_name_folder').val()
            },
            success: function (data) {
                $('#imgr__content').html(data.content);
                console.log(data.content);

                console.log(sessionStorage.getItem('id_folder'));
            }
        });
    });
</script>



