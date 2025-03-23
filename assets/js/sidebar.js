document.addEventListener('DOMContentLoaded', () => {
    // Votre script existant pour les menus déroulants
    const toggleButtons = document.querySelectorAll('[id^="toggle"]');

    toggleButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const menuId = button.getAttribute('id').replace('toggle', 'menu');
            const menu = document.getElementById(menuId);
            menu.classList.toggle('hidden');

            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !button.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    });

    // Gestion du menu de profil utilisateur
    const userProfileContainer = document.getElementById('userProfileContainer');
    userProfileContainer.addEventListener('click', () => {
        const userMenu = document.getElementById('userMenu');
        userMenu.classList.toggle('hidden');
    });

    // Fermer le menu utilisateur si on clique en dehors
    document.addEventListener('click', (e) => {
        if (!userProfileContainer.contains(e.target)) {
            const userMenu = document.getElementById('userMenu');
            if (!userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
            }
        }
    });
});
