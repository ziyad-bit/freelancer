    /* eslint-disable no-undef */
Dropzone.autoDiscover = false;

let files_number = 0;

new Dropzone('#image_upload', {
    thumbnailWidth: 200,
    acceptedFiles : '.jpg,.png,.jepg,.gif,.webp',
    paramName     : 'image',
    addRemoveLinks: true,
    maxFilesize   : 8 * 1024,
    maxFiles      : 4,

    success: function (data) {
        let response      = JSON.parse(data.xhr.response);
        let file_name     = response.file_name;
        let original_name = response.original_name;
        let form_ele      = document.getElementById('form');

        files_number++;

        const html = `<div class="${original_name}">
                        <input type = "hidden"  name = "files[${files_number}][name]" value = "${file_name}">
                        <input type = "hidden"  name = "files[${files_number}][type]" value = "image">
                    </div>`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let   image_name  = file.name;
        const hiddenInput = document.getElementsByClassName(`${image_name}`)[0];

        hiddenInput.remove();

        file.previewElement.remove();
    }
})


new Dropzone('#file_upload', {
    thumbnailWidth: 200,
    acceptedFiles : '.pdf,.ppt.,.doc,.xls',
    paramName     : 'application',
    addRemoveLinks: true,
    maxFilesize   : 20 * 1024,

    success: function (data) {
        let response      = JSON.parse(data.xhr.response);
        let file_name     = response.file_name;
        let original_name = response.original_name;
        let form_ele      = document.getElementById('form');

        files_number++;

        const html = `<div class="${original_name}">
                        <input type = "hidden" name = "files[${files_number}][name]" value = "${file_name}">
                        <input type = "hidden" name = "files[${files_number}][type]" value = "application">
                    </div>`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let   file_name   = file.name;
        const hiddenInput = document.getElementsByClassName(`.${file_name}`)[0];

        hiddenInput.remove();

        file.previewElement.remove();
    }
})

new Dropzone('#video_upload', {
    thumbnailWidth: 200,
    acceptedFiles : '.mp4,.mov,.flv,.avi',
    paramName     : 'video',
    addRemoveLinks: true,
    maxFilesize   : 150 * 1024,

    success: function (data) {
        let response      = JSON.parse(data.xhr.response);
        let file_name     = response.file_name;
        let original_name = response.original_name;
        let form_ele      = document.getElementById('form');

        files_number++;

        const html = `<div class="${original_name}">
                        <input type = "hidden"  name = "files[${files_number}][name]" value = "${file_name}">
                        <input type = "hidden"  name = "files[${files_number}][type]" value = "video">
                    </div>`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let   video_name  = file.name;
        const hiddenInput = document.getElementsByClassName(`.${video_name}`)[0];

        hiddenInput.remove();

        file.previewElement.remove();
    }
})



