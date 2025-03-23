document.addEventListener('DOMContentLoaded', function () {
    var dropdowns = ['FactureDevis', 'Gestion', 'Comptabilite'];
    dropdowns.forEach(function(dropdown) {
        var button = document.getElementById('dropdown' + dropdown);
        var menu = document.getElementById('menu' + dropdown);
        var icon = document.getElementById('icon' + dropdown);

        button.addEventListener('click', function() {
            menu.classList.toggle('hidden');
            // Faire pivoter l'icône de 180 degrés quand le menu est ouvert
            icon.classList.toggle('rotate-180');
        });
    });
});

