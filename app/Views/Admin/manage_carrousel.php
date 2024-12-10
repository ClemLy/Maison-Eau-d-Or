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
	<div class="container my-5">
    <h2 class="mb-4">Gestion du carrousel des catégories</h2>
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
        <button type="submit" class="btn btn-primary mt-3">Enregistrer les modifications</button>
    </form>
</div>

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