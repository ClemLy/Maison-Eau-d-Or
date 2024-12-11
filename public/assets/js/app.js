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








function removeItem(productId, event) {
    console.log("Suppression du produit avec ID:", productId);

    event.preventDefault();

    const itemToRemove = document.querySelector(`.cart-item[data-id-prod="${productId}"]`);
    if (itemToRemove) {
        itemToRemove.classList.add('removing');

        setTimeout(() => {
            itemToRemove.remove();
            updateCartDisplay();
        }, 600); 
    }

    // Effectuer la suppression côté serveur sans rediriger
    fetch(`/panier/supprimer/${productId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Produit supprimé du panier côté serveur.');
        } else {
            alert('Erreur lors de la suppression du produit');
        }
    });
}


// Fonction pour mettre à jour le contenu de l'Offcanvas avec les produits du panier
document.addEventListener('DOMContentLoaded', function() {
    const panierHeader = document.getElementById('panierHeader');
    if (panierHeader) {
        panierHeader.addEventListener('click', updateCartDisplay);
    } else {
        console.error("L'élément avec l'ID 'panierHeader' n'a pas été trouvé.");
    }
});
// Fonction pour mettre à jour l'affichage du panier
function updateCartDisplay() {
    fetch('/panier/actualiser') // Récupérer les données actualisées du panier
        .then(response => response.json())
        .then(data => {
            console.log('Réponse du serveur :', data); // Log pour déboguer

            const cartContainer = document.querySelector('.cart-items');
            const cartTotalContainer = document.querySelector('.cart-total');
            const cartButtonsContainer = document.querySelector('.cart-buttons');
            const emptyCartMessage = document.querySelector('.empty-cart-message');

            // Réinitialiser le panier avant de remplir à nouveau
            cartContainer.innerHTML = '';
            cartTotalContainer.innerHTML = '';
            cartButtonsContainer.innerHTML = '';

            // Vérification si la réponse contient bien des articles du panier
            if (data.success && Array.isArray(data.cartItems)) {
                if (data.cartItems.length > 0) {
                    // Affichage des articles du panier
                    data.cartItems.forEach(item => {
                        const cartItemElement = document.createElement('div');
                        cartItemElement.classList.add('cart-item', 'd-flex', 'align-items-center', 'mb-3');
                        cartItemElement.setAttribute('data-id-prod', item.id_prod);
                        cartItemElement.innerHTML = `
                            <img src="${item.img_path}" alt="Produit" class="cart-item-img">
                            <div class="cart-item-info ms-3">
                                <h6 class="cart-item-title mb-1">${item.p_name}</h6>
                                <p class="cart-item-price mb-1">${parseFloat(item.p_price).toFixed(2)} €</p>
                                <p class="cart-item-quantity">Qté : ${item.quantity}</p>
                            </div>
                            <a href="#" class="btn ms-auto text-danger" aria-label="Remove" onclick="removeItem(${item.id_prod}, event)">
                                <i class="bi bi-trash"></i>
                            </a>
                        `;
                        cartContainer.appendChild(cartItemElement);
                    });

                    // Affichage du total
                    const totalAmount = data.cartItems.reduce((sum, item) => sum + item.p_price * item.quantity, 0);
                    cartTotalContainer.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <span>Total :</span>
                            <strong>${totalAmount.toFixed(2)} €</strong>
                        </div>
                    `;

                    // Affichage des boutons d'action
                    cartButtonsContainer.innerHTML = `
                    <div class="mt-4">
                        <a href="${panierUrl}" class="btn btn-gold-hover w-100 text-dark">Afficher les détails</a>
                        <a href="${commanderUrl}" class="btn btn-gold-hover w-100 text-dark mt-2">Passer la commande</a>
                        <a href="${viderPanierUrl}" class="btn btn-vider w-100 mt-2">Vider le Panier</a>
                    </div>
                `;
                

                    // Masquer le message "Votre panier est vide"
                    emptyCartMessage.classList.add('d-none');
                    // Afficher les boutons d'action
                    cartButtonsContainer.classList.remove('d-none');
                } else {
                    // Si le panier est vide, afficher le message
                    cartContainer.innerHTML = '';
                    cartTotalContainer.innerHTML = '';
                    cartButtonsContainer.classList.add('d-none');
                    emptyCartMessage.classList.remove('d-none');
                }
            } else {
                // Si la réponse du serveur est incorrecte
                console.error('Erreur dans la réponse du serveur ou format inattendu :', data);
                cartContainer.innerHTML = '<p class="text-center">Erreur de récupération du panier.</p>';
            }
        });
}




// Fonction pour mettre à jour le total du panier
function updateTotal(cartItems) {
    const totalElement = document.querySelector('.cart-total');
    const totalAmount = cartItems.reduce((sum, item) => sum + item.p_price * item.quantity, 0);
    totalElement.innerHTML = `
        <div class="d-flex justify-content-between">
            <span>Total :</span>
            <strong>${totalAmount.toFixed(2)} €</strong>
        </div>
    `;
}
