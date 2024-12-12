<style>
	.account-list-item {
		display: flex;
		flex-direction: column;
		justify-content: center;
		border: 1px solid #e9ecef;
		border-radius: 20%;
		width: 175px;
		height: 175px;
		transition: transform 0.2s ease, box-shadow 0.2s ease;
		text-align: center;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		margin-right: 100px;
	}
</style>

<div class="page-container mt-5" style="display: flex; flex-direction: column; align-items: center;">


	<h1>Mon Compte</h1>
	

	<div class="page-content" style="width: 50%;">


	<?php if (session()->getFlashdata('success')) : ?>
		<p class="alert alert-success"><?= session()->getFlashdata('success'); ?></p>
	<?php endif; ?> 
	

		<h2> Information du compte </h2>

		<table class="account">
			<tr>
				<td>Nom :</td>
				<td><?= esc(session()->get('first_name')); ?> <?= esc(session()->get('last_name')); ?></td>
			</tr>
			<tr>
				<td>Email : </td>
				<td><?= esc(session()->get('email')); ?></td>
			</tr>
			<tr>
				<td>Téléphone :</td>
				<!-- Affiche le numéro de téléphone si renseigné, sinon affiche "Non renseigné" et mettre un . entre chaque duo de chiffre sauf le dernier-->
				<td><?= session()->get('phone_number') ? esc(chunk_split(session()->get('phone_number'), 2, '.')) : 'Non renseigné'; ?></td>
			</tr>
			<tr>
				<td> Newsletter :</td>
				<td><?= session()->get('newsletter') ? 'Inscrit  <i class="bi bi-check-circle-fill" style="color:#090"></i>' : 'Non inscrit <i class="bi bi-x-circle-fill" style="color:#F00"></i>'; ?></td>
			</tr>
		</table>
	</div>
	<ul class="account-list">
		<div class="row">
				<li class="account-list-item logo-admin">
					<i class="bi bi-pencil-square"></i> <!-- Exemple d'icône Bootstrap -->
					<a href="<?= site_url('account/update'); ?>" class="text-decoration-none logo-admin">
						Modifier mes informations
					</a>
				</li>
				<li class="account-list-item erase">
					<i class="bi bi-box-arrow-left"></i> <!-- Exemple d'icône Bootstrap -->
					<a href="<?= site_url('logout'); ?>">Se déconnecter</a>
				</li>
				<li class="account-list-item logo-admin">
					<i class="bi bi-trash3"></i>
					<a href="<?= site_url('account/delete'); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');" class="confirm-delete">Supprimer mon compte</a></li>
				</li>
            </div>
		</div>
	</div>
</div>

<?php
return ($_SESSION);
?>