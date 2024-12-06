document.addEventListener('DOMContentLoaded', function () {
    const mediaLibrary = document.getElementById('media-library');
    const existingImgsInput = document.getElementById('existing_imgs');
    const selectedCountElement = document.getElementById('selected-count');

    // Initialiser les images sélectionnées à partir de l'input caché
    let selectedIds = existingImgsInput.value ? existingImgsInput.value.split(',') : [];

    // Mettre à jour les bordures des images déjà sélectionnées au chargement
    const imageCards = document.querySelectorAll('.image-card');
    imageCards.forEach(card => {
        const cardId = card.getAttribute('data-id');
        if (selectedIds.includes(cardId)) {
            card.classList.add('border-primary');
        }
    });

    // Gérer les clics sur les images
    mediaLibrary.addEventListener('click', function (e) {
        const card = e.target.closest('.image-card');
        if (card) {
            const selectedId = card.getAttribute('data-id');

            // Ajouter ou retirer l'image des sélectionnées
            if (selectedIds.includes(selectedId)) {
                selectedIds = selectedIds.filter(id => id !== selectedId);
                card.classList.remove('border-primary'); // Retirer la bordure
            } else {
                selectedIds.push(selectedId);
                card.classList.add('border-primary'); // Ajouter la bordure
            }

            // Mettre à jour l'input caché et le compteur
            existingImgsInput.value = selectedIds.join(',');
            selectedCountElement.textContent = `Images sélectionnées : ${selectedIds.length}`;
        }
    });

    // Gérer l'upload d'image via AJAX
    document.getElementById('uploadBtn').addEventListener('click', function () {
        const fileInput = document.getElementById('new_img');
        const file = fileInput.files[0];

        if (!file) {
            alert('Veuillez sélectionner une image à uploader.');
            return;
        }

        // Vérification du type et de la taille du fichier
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Seules les images JPEG, PNG et GIF sont acceptées.');
            return;
        }

        if (file.size > 5 * 1024 * 1024) { // Limite de 5 Mo
            alert('L\'image ne doit pas dépasser 5 Mo.');
            return;
        }

        const formData = new FormData();
        formData.append('new_img', file);

        fetch('/image/upload', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ajouter l'image uploadée dans la médiathèque
                    const newCard = document.createElement('div');
                    newCard.classList.add('col-md-3');
                    newCard.innerHTML = `
                        <div class="card image-card" data-id="${data.image.id_img}">
                            <img src="${data.image.img_path}" alt="${data.image.img_name}" class="card-img-top" style="cursor: pointer;">
                        </div>
                    `;
                    mediaLibrary.appendChild(newCard);

                    // Réinitialiser le champ fichier
                    fileInput.value = '';

                    // Ajouter l'événement de sélection pour la nouvelle image
                    newCard.querySelector('.image-card').addEventListener('click', function () {
                        const card = this;
                        card.classList.toggle('border-primary');
                        const selectedId = card.getAttribute('data-id');
                        let selectedIds = existingImgsInput.value ? existingImgsInput.value.split(',') : [];

                        if (selectedIds.includes(selectedId)) {
                            selectedIds = selectedIds.filter(id => id !== selectedId);
                        } else {
                            selectedIds.push(selectedId);
                        }

                        existingImgsInput.value = selectedIds.join(',');
                        selectedCountElement.textContent = `Images sélectionnées : ${selectedIds.length}`;
                    });
                } else {
                    alert('Erreur lors de l\'upload : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'upload.');
            });
    });

    const container = document.getElementById('categories-container');
    const input = document.getElementById('categories');
    const newCategoryInput = document.getElementById('new-category');
    const addCategoryBtn = document.getElementById('add-category-btn');

    // Charger les catégories existantes
    let categories = JSON.parse(input.value || '[]');

    function renderCategories() {
        container.innerHTML = ''; // Réinitialiser le conteneur
        categories.forEach(category => {
            const tag = document.createElement('div');
            tag.classList.add('category-tag');
            tag.textContent = category;

            // Bouton de suppression
            const removeButton = document.createElement('span');
            removeButton.textContent = '×';
            removeButton.classList.add('remove-category');
            removeButton.addEventListener('click', function () {
                categories = categories.filter(c => c !== category);
                input.value = JSON.stringify(categories); // Mettre à jour le champ caché
                renderCategories(); // Rafraîchir l'affichage
            });

            tag.appendChild(removeButton);
            container.appendChild(tag);
        });

        // Mettre à jour le champ caché
        input.value = JSON.stringify(categories);
    }

    function addCategory(category) {
        category = category.trim();
        if (!category || categories.includes(category)) {
            alert('Cette catégorie est déjà ajoutée ou est invalide.');
            return;
        }

        categories.push(category);
        renderCategories(); // Rafraîchir l'affichage
    }

    addCategoryBtn.addEventListener('click', function () {
        const category = newCategoryInput.value;
        if (category) {
            addCategory(category);
            newCategoryInput.value = ''; // Réinitialiser le champ d'entrée
        }
    });

    newCategoryInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const category = newCategoryInput.value;
            if (category) {
                addCategory(category);
                newCategoryInput.value = ''; // Réinitialiser le champ d'entrée
            }
        }
    });

    // Initialiser l'affichage des catégories existantes
    renderCategories();
});