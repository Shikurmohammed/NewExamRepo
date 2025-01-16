ClassicEditor
    .create(document.querySelector('.ckeditor'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });

// ClassicEditor
// .create(document.querySelector('.ckeditor'))
// .then(editor => {
//     console.log(editor);
// })
// .catch(error => {
//     console.error(error);
// });