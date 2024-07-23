Dropzone.autoDiscover = false;

new Dropzone('#image_upload', {
    thumbnailWidth: 200,
    acceptedFiles: '.jpg,.png,.jepg,.gif,.webp',
    paramName: 'image',
    addRemoveLinks: true,
    maxFilesize: 8000,
    maxFiles: 4,

    success: function (data) {
        let response = JSON.parse(data.xhr.response);
        let image_name = response.file_name;
        let original_name = response.original_name;
        let form_ele = document.getElementById('form');

        const html = `<input type="hidden" class="${original_name}" name="images[]" value="image-${image_name}">
                    <input type="hidden" class="${original_name}" name="files[]" value="image-${image_name}">`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let image_name = file.name;

        const hiddenInputs = document.getElementsByClassName(`${image_name}`);

        hiddenInputs.forEach(hiddenInput => {
            hiddenInput.remove();
        });

        file.previewElement.remove();
    }
})


new Dropzone('#file_upload', {
    thumbnailWidth: 200,
    acceptedFiles: '.pdf,.ppt.,.doc,.xls',
    paramName: 'application',
    addRemoveLinks: true,
    maxFilesize: 20000,

    success: function (data) {
        let response = JSON.parse(data.xhr.response);
        let file_name = response.file_name;
        let original_name = response.original_name;
        let form_ele = document.getElementById('form');

        const html = `<input type="hidden" class="${original_name}" name="applications[]" value="application-${file_name}">
        <input type="hidden" class="${original_name}" name="files[]" value="application-${file_name}">`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let file_name = file.name;

        const hiddenInputs = document.getElementsByClassName(`${file_name}`);

        hiddenInputs.forEach(hiddenInput => {
            hiddenInput.remove();
        });

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

        const html = `<input type="hidden" class="${original_name}" name="videos[]" value="video-${file_name}">
        <input type="hidden" class="${original_name}" name="files[]" value="video-${file_name}">`;

        form_ele.insertAdjacentHTML('beforeend', html);
    },
    removedfile: function (file) {
        let video_name = file.name;

        const hiddenInputs = document.getElementsByClassName(`${video_name}`);

        hiddenInputs.forEach(hiddenInput => {
            hiddenInput.remove();
        });

        file.previewElement.remove();
    }
})

