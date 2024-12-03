<h1>Modifier Mon Compte</h1>

<form action="<?= site_url('account/update'); ?>" method="post">
	<label for="last_name">Nom :</label>
	<input type="text" id="last_name" name="last_name" value="<?= old('last_name', session()->get('last_name')); ?>" required>

	<label for="first_name">Prénom :</label>
	<input type="text" id="first_name" name="first_name" value="<?= old('first_name', session()->get('first_name')); ?>" required>

	<label for="email">Email :</label>
	<input type="email" id="email" name="email" value="<?= old('email', session()->get('email')); ?>" required>

	<label for="phone_number">Numéro de téléphone :</label>
	<input type="text" id="phone_number" name="phone_number" value="<?= old('phone_number', session()->get('phone_number')); ?>">

	<button type="submit">Enregistrer les modifications</button>
</form>

<?php if (isset($validation)) : ?>
	<div style="color: red;">
		<?= $validation->listErrors(); ?>
	</div>
<?php endif; ?>