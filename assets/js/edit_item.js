import plupload from 'plupload';
import '../css/edit-item.css'

let uploader = new plupload.Uploader({
    browse_button: 'item_Browse',
    url: '/item/upload',

    filters: {
        max_file_size: '10mb',
        mime_types: [
            {title: "Image files", extensions: "jpg,jpeg,png"},
        ]
    },

    init: {
        PostInit: function () {
            document.getElementById('filelist').innerHTML = '';
            $('#myProgress').hide();

            document.getElementById('item_Upload').onclick = function () {
                $('#myProgress').show();
                uploader.start();
                return false;
            };
        },

        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                $('#myBar').css('width', 1 + '%');
                $('#myProgress').show();
            });
        },

        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            $('#myProgress').show();
            $('#myBar').css('width', file.percent + '%');
        },

        FileUploaded: function () {
            $('#myProgress').hide();
        },

        UploadComplete: function (up, files) {
            $('#myProgress').hide();
            $("#item_pictures").val(files);
        },

        Error: function (up, err) {
            document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
        }
    }
});

uploader.init();