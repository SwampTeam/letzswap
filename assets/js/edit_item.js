import plupload from 'plupload';

let uploader = new plupload.Uploader({
    browse_button: 'item_Browse', // this can be an id of a DOM element or the DOM element itself
    url: '/item/upload'
});

uploader.init();

document.getElementById('item_Upload').onclick = function () {
    uploader.start();
};