<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Gestion des Carrousels</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
		<style>
			.draggable {
				cursor: grab;
				display: flex;
				align-items: center;
				justify-content: space-between;
			}

			.dragging {
				opacity: 0.5;
			}

			.inactive {
				opacity: 0.5;
				pointer-events: none;
			}

			.grip-icon {
				margin-right: 10px;
				cursor: grab;
				font-size: 1.5rem;
				color: #6c757d;
			}

			.dropzone {
				border: 2px dashed #ccc;
				border-radius: 5px;
				padding: 10px;
			}

			.inactive {
				opacity: 0.5;
				/* Désactiver le glisser-déposer mais garder l'interaction avec la checkbox */
				pointer-events: auto;
				cursor: not-allowed;
			}
		</style>
	</head>

	<body>
		<div class="page-container">
			<div class="page-content">
				<!-- Formulaire pour modifier les images du carrousel principal -->
				<h1 class="mb-4">Gestion du carrousel principal</h1>

				<div class="row mb-5">
					<?php for ($i = 0; $i < 3; $i++): ?>
						<div class="col-md-4 text-center">
							<div class="image-slot border border-secondary p-4 position-relative" data-index="<?= $i ?>" data-id="<?= isset($carrousel[$i]) ? esc($carrousel[$i]['id_img']) : '' ?>">
								<?php if (isset($carrousel[$i])): ?>
									<span class="placeholder-icon fs-1 d-none"> + </span>
									<img src="<?= esc($carrousel[$i]['img_path']) ?>" alt="Image <?= $i + 1 ?>" class="selected-image" style="width: 100%; height: auto;">
								<?php else: ?>
									<span class="placeholder-icon fs-1">+</span>
									<img src="" alt="Image <?= $i + 1 ?>" class="selected-image d-none" style="width: 100%; height: auto;">
								<?php endif; ?>
								<button type="button" class="btn btn-primary btn-sm position-absolute top-50 start-50 translate-middle" data-bs-toggle="modal" data-bs-target="#imageModal" data-index="<?= $i ?>">
									+
								</button>
							</div>
						</div>
					<?php endfor; ?>
				</div>

				<!-- Bouton pour soumettre -->
				<button id="saveCarouselBtn" class="btn btn-primary">Enregistrer le carrousel principal</button>

				<!-- Modale pour sélectionner une image -->
				<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="imageModalLabel">Sélectionner une image</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="mb-3">
									<label for="new_img" class="form-label">Uploader une nouvelle image :</label>
									<input type="file" id="new_img" name="new_img" class="form-control" accept="image/*">
									<button id="uploadBtn" type="button" class="btn btn-primary mt-2">Uploader</button>
								</div>
								
								<div class="mb-3">
									<label class="form-label">Choisir une image :</label>
									<div id="media-library" class="row">
										<?php if (isset($images)): ?>
											<!-- Boucle PHP pour afficher les images -->
											<?php foreach ($images as $image): ?>
												<div class="col-md-3">
													<div class="card image-card" data-id="<?= $image['id_img'] ?>" style="cursor: pointer;">
														<img src="<?= $image['img_path'] ?>" alt="<?= $image['img_name'] ?>" class="card-img-top">
													</div>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
									<input type="hidden" id="existing_imgs" name="existing_imgs">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
							</div>
						</div>
					</div>
				</div>


				<!-- Formulaire pour modifier les images du carrousel catégories -->
				<div class="container my-5">
					<h1 class="mb-4">Gestion du carrousel des catégories</h1>
					<form id="reorderForm" method="post" action="<?= site_url('admin/carrousel/modifierCategorie'); ?>">
						<ul id="draggable-list" class="list-group dropzone">
							<?php foreach ($categories as $category): ?>
								<li class="list-group-item draggable" 
									draggable="true" 
									data-id="<?= esc($category['id_cat']) ?>"
									data-position="<?= esc($category['position']) ?>">
									<span class="grip-icon">&#x2630;</span>
									<?= esc($category['cat_name']) ?>
									<div>
										<input type="checkbox" 
											class="toggle-active" 
											<?= $category['active'] ? 'checked' : '' ?> 
										>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
						<button type="submit" class="btn btn-primary mt-3">Enregistrer le carrousel catégories</button>
					</form>
				</div>
			</div>
		</div>



		<!-- Scripts pour carrousel principal -->
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				let selectedIndex = null;

				// Quand un bouton "+" est cliqué
				document.querySelectorAll('.image-slot button').forEach(button => {
					button.addEventListener('click', (e) => {
						selectedIndex = e.target.getAttribute('data-index');
					});
				});

				// Quand une image est cliquée dans la bibliothèque
				document.querySelectorAll('.image-card').forEach(card => {
					card.addEventListener('click', (e) => {
						if (selectedIndex !== null) {
							const selectedSlot = document.querySelector(`.image-slot[data-index="${selectedIndex}"]`);
							const selectedImageSrc = card.querySelector('img').src;
							const selectedImageId = card.getAttribute('data-id');

							// Mettre à jour l'image dans le carré
							selectedSlot.querySelector('.selected-image').src = selectedImageSrc;
							selectedSlot.querySelector('.selected-image').classList.remove('d-none');
							selectedSlot.querySelector('.placeholder-icon').classList.add('d-none');

							// Ajouter l'ID de l'image dans le data-attribute du slot
							selectedSlot.setAttribute('data-id', selectedImageId);
						}

						// Assurez-vous de fermer correctement la modale
						const modalElement = document.getElementById('imageModal');
						const modal = bootstrap.Modal.getInstance(modalElement);
						modal.hide(); // Fermer la modale

						// Nettoyer le backdrop au cas où
						setTimeout(() => {
							const backdrops = document.querySelectorAll('.modal-backdrop');
							backdrops.forEach(backdrop => backdrop.remove());
							document.body.classList.remove('modal-open');
							document.body.style = ""; 
						}, 100);
					});
				});

				// Gestion du bouton Enregistrer
				document.getElementById('saveCarouselBtn').addEventListener('click', (e) => {
					e.preventDefault();

					// Récupérer les images sélectionnées
					const selectedImages = [...document.querySelectorAll('.image-slot')].map(slot => ({
						id: slot.getAttribute('data-id') || null
					}));

					// Envoyer les données au serveur
					fetch('<?= site_url('admin/carrousel/modifierMain'); ?>', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: JSON.stringify({ images: selectedImages })
					})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								alert('Carrousel principal mis à jour avec succès.');
							} else {
								alert('Erreur : ' + data.message);
							}
						})
						.catch(error => console.error('Erreur :', error));
				});
			});
		</script>




		<!-- Scripts pour carrousel catégories -->
        <script>
			document.addEventListener('DOMContentLoaded', () => {
				const list = document.getElementById('draggable-list');
				let draggedItem = null;

				// Gestion du drag and drop
				list.addEventListener('dragstart', (e) => {
					const listItem = e.target;
					if (listItem.classList.contains('inactive')) {
						e.preventDefault(); // Empêche le drag si l'élément est inactif
						return;
					}
					draggedItem = listItem;
					listItem.classList.add('dragging');
				});

				list.addEventListener('dragend', (e) => {
					e.target.classList.remove('dragging');
					draggedItem = null;
				});

				list.addEventListener('dragover', (e) => {
					e.preventDefault();
					const afterElement = getDragAfterElement(list, e.clientY);
					if (afterElement == null) {
						list.appendChild(draggedItem);
					} else {
						list.insertBefore(draggedItem, afterElement);
					}
				});

				function getDragAfterElement(container, y) {
					const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')];
					return draggableElements.reduce((closest, child) => {
						const box = child.getBoundingClientRect();
						const offset = y - box.top - box.height / 2;
						if (offset < 0 && offset > closest.offset) {
							return { offset: offset, element: child };
						} else {
							return closest;
						}
					}, { offset: Number.NEGATIVE_INFINITY }).element;
				}

				// Activation/désactivation des éléments via la checkbox
				document.querySelectorAll('.toggle-active').forEach(checkbox => {
					checkbox.addEventListener('change', function () {
						const listItem = this.closest('.list-group-item');
						if (this.checked) {
							listItem.classList.remove('inactive');
						} else {
							listItem.classList.add('inactive');
						}
					});
				});

				// Soumission du formulaire avec JSON
				document.getElementById('reorderForm').addEventListener('submit', (e) => {
					e.preventDefault();

					// Créer un tableau d'objets avec l'ID et l'état de la catégorie
					const reorderedItems = [...list.querySelectorAll('.list-group-item')].map((item, index) => ({
						id: item.getAttribute('data-id'),
						active: !item.classList.contains('inactive'),
						position: index + 1 // Ordre dans la liste
					}));

					console.log(JSON.stringify(reorderedItems));

					// Envoi des données au serveur
					fetch('<?= site_url('admin/carrousel/modifierCategorie'); ?>', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: JSON.stringify({ categories: reorderedItems })
					})
							});
							});
		</script>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>