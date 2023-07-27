Dropzone.autoDiscover = false;

new Dropzone('#image_upload', {
    thumbnailWidth: 200,
    acceptedFiles: '.jpg,.png,.jepg,.gif,.webp',
    paramName: 'image',
    addRemoveLinks: true,
    maxFilesize: 8000,
    maxFiles:2,

    success: function (data) {
        let response = JSON.parse(data.xhr.response);
        let image_name = response.file_name;
        let original_name = response.original_name;
        let form_ele = document.getElementById('form');

        const html = `<input type="hidden" id="${original_name}" name="images[]" value="image${image_name}">`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let image_name = file.name;

        document.getElementById(`${image_name}`).remove();

        file.previewElement.remove();
    }
})


new Dropzone('#file_upload', {
    thumbnailWidth: 200,
    acceptedFiles: '.pdf,.ppt.,.doc,.xls',
    paramName: 'file',
    addRemoveLinks: true,
    maxFilesize: 20000,

    success: function (data) {
        let response = JSON.parse(data.xhr.response);
        let file_name = response.file_name;
        let original_name = response.original_name;
        let form_ele = document.getElementById('form');

        const html = `<input type="hidden" id="${original_name}" name="files[]" value="files${file_name}">`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let file_name = file.name;

        document.getElementById(`${file_name}`).remove();

        file.previewElement.remove();
    }
})

new Dropzone('#video_upload', {
    thumbnailWidth: 200,
    acceptedFiles: '.mp4,.mov,.flv,.avi',
    paramName: 'video',
    addRemoveLinks: true,
    maxFilesize: 100000,

    success: function (data) {
        let response = JSON.parse(data.xhr.response);
        let file_name = response.file_name;
        let original_name = response.original_name;
        let form_ele = document.getElementById('form');

        const html = `<input type="hidden" id="${original_name}" name="videos[]" value="video${file_name}">`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let file_name = file.name;

        document.getElementById(`${file_name}`).remove();

        file.previewElement.remove();
    }
})

