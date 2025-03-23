document.addEventListener('DOMContentLoaded', function() {
    var labels = document.querySelectorAll('label[data-target]');
    labels.forEach(function(label) {
        var targetId = label.getAttribute('data-target');
        var input = document.getElementById(targetId);
        input.addEventListener('change', function() {
            var fileName = this.files[0].name;
            label.textContent = ' ' + fileName;
        });
    });
});
