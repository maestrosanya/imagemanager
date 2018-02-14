
<div class="imgr__wrapper">
    <div class="imgr__box">
        <div class="imgr__header">

            <div class="imgr__header__btn">
                <button id="imgr_btn_back"><span><i class="fa fa-level-up" aria-hidden="true"></i></span> Назад</button>
                <button id="imgr_btn_show_image_input"><span><i class="fa fa-picture-o" aria-hidden="true"></i></span> add image</button>
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

            <div class="imgr__header__input__folder">
                <div class="form-group">
                    <input type="text" id="new_name_folder" class="new_name_folder" name="new_name_folder">
                    <button id="imgr_btn_add_folder">Создать</button>
                </div>
            </div>

                <div class="imgr__header__input__image">
                    <div class="form-group">
                        <form id="imgr_uploads_form">
                            <input type="file" id="uploads_new_images" class="uploads_new_images" name="uploads_new_images[]" multiple="multiple" accept="image/jpeg, image/png, image/gif">
                            <button id="imgr_btn_add_images">Добавить</button>
                        </form>
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
    $('.imgr__header__input__folder').hide();

    $('#imgr_btn_show_folder_input').click(function (e) {
        e.preventDefault();

        $('.imgr__header__input__folder').stop(true, true).toggle(500);

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



    /* Добавляет новое изображение */
    $('#imgr_btn_show_image_input').click(function (e) {
        e.preventDefault();

        $('.imgr__header__input__image').stop(true, true).toggle(500);

    });

    $('#imgr_uploads_form').on('submit', function (e) {

        e.preventDefault();

        var thisForm = $(this)[0];

        var formData = new FormData(thisForm);

        $.ajax({
            type: 'POST',
            url: '/admin/image-manager/add-image',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            data: formData,
            dataType: 'json',
            success: function(data){
                if(data){
                    console.log(data.content);
                    console.log(data.res);
                }
            }
        });



    });

    /*var formData;

    $('#uploads_new_images').on('change', function () {

        formData = new FormData(document.querySelector('#imgr_uploads_form'));

    });



    $('#imgr_btn_add_images').click(function (e) {

        e.preventDefault();

        var files = $('#uploads_new_images').serialize();

        console.log(formData);

    });*/

</script>



