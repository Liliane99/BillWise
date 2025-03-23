document.addEventListener('DOMContentLoaded', () => {
    const paginationContainer = document.getElementById('paginationControls');

    if (paginationContainer) {
        paginationContainer.addEventListener('click', (event) => {
            const target = event.target;

            if (target.tagName === 'A' && target.classList.contains('pagination-link')) {
                event.preventDefault();
                const page = target.getAttribute('data-page');
                console.log("Chargement de la page :", page); // Ajoutez cette ligne pour le débogage
                loadUsers(page);
            }
        });
    }
});


function loadUsers(page) {
    const url = `/user/?page=${page}`; // Assurez-vous que l'URL correspond à votre route Symfony
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.querySelector('#usersTable').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}
