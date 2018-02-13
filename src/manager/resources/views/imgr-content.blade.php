@if(isset($errors))
<div>
    @foreach($errors->all() as $error)
        <div class="imgr__errors">{{ $error }}</div>
    @endforeach
</div>
@endif

<div class="imgr__content" id="imgr__content">
    <input type="text" id="imgr_parent_folder" value="{{ isset($back) ? $back : '0' }}" hidden>
        <div class="row">
            <div class="col-sm-3">
                @if(isset($currentFolder) and $currentFolder != 'parent')
                    <div class="col">
                        <div><i class="fa fa-folder-open-o" aria-hidden="true"></i> {{ $currentFolder }}</div>
                    </div>
                @endif
                @if(isset($folders))
                    @foreach($folders as $folder)
                        <div class="col">
                            <i class="fa fa-folder-o" aria-hidden="true"></i>
                            <a class="imgr_folder_btn" href="{{ $folder->id }}" data-parent_folder="{{ $folder->parent_folder }}">{{ $folder->name }}</a>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="col-sm-9">
                <div class="row">
                    @if(isset($images) and $images != 'parent')
                        @foreach($images as $image)
                            <div class="col-3 col-sm-4 col-md-3 col-lg-3">
                                <img src="{{ asset('storage/uploads') .'/'.  $image->file_name}}"  alt="{{ $image->original_name }}" width="100%" height="auto">
                                <p>
                                    <input type="checkbox" name="images['name'][]" value="{{ $image->file_name }}">
                                    {{ $image->original_name }}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

</div>


<script>

    $('.imgr_folder_btn').click(function (e) {

        e.preventDefault();

        var id_folder       = $(this).attr('href');
        var parent_folder   = $(this).attr('href');

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
