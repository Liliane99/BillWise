document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('profilePictureFile');
    var imagePreview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.classList.remove('hidden');
        } else {
            imagePreview.classList.add('hidden');
        }
    });
});
