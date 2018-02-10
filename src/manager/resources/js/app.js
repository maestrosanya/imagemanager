

function openImageManager(htmlElement) {

    var id_folder = 0;

    sessionStorage.setItem('id_folder', 0);

    $(htmlElement).click(function (e) {

        e.preventDefault();

        $.ajax({
            type: 'GET',
            url: '/admin/image-manager',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                $('body').prepend(data.manager);
                console.log(data.manager);

                console.log(sessionStorage.getItem('id_folder'));
            }
        });
    });

}
openImageManager('.open-image-manager');

