<h1>Modifier Mon Compte</h1>

<form action="<?= site_url('compte/update'); ?>" method="post">
	<label for="nom_user">Nom :</label>
	<input type="text" id="nom_user" name="nom_user" value="<?= old('nom_user', session()->get('nom_user')); ?>" required>

	<label for="prenom_user">Prénom :</label>
	<input type="text" id="prenom_user" name="prenom_user" value="<?= old('prenom_user', session()->get('prenom_user')); ?>" required>

	<label for="email_user">Email :</label>
	<input type="email" id="email_user" name="email_user" value="<?= old('email_user', session()->get('email_user')); ?>" required>

	<button type="submit">Enregistrer les modifications</button>
</form>

<?php if (isset($validation)) : ?>
	<div style="color: red;">
		<?= $validation->listErrors(); ?>
	</div>
<?php endif; ?>