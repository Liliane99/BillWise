document.getElementById('generatePassword').addEventListener('click', function() {
    var passwordField = document.querySelector('input[name="user[password]"]');
    passwordField.value = generatePassword(12); // Générer un mot de passe de 12 caractères
});

function generatePassword(length) {
    var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    var retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}
