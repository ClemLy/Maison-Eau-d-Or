<div class="form-container">
	<a href="<?= site_url('account'); ?>" class="btn btn-secondary mb-3" style="border: none;">
		<i class="bi bi-arrow-left"></i>
	</a>

	<h1>Modifier Mon Compte</h1>

	<?php if (isset($validation)) : ?>
		<div class="alert alert-danger">
			<ul class="text-start">
				<?= $validation->listErrors(); ?>
			</ul>
		</div>
	<?php endif; ?>

	<form action="<?= site_url('account/update'); ?>" method="post">
		<label for="last_name">Nom</label>
		<input type="text" id="last_name" name="last_name" value="<?= old('last_name', session()->get('last_name')); ?>">

		<label for="first_name">Prénom</label>
		<input type="text" id="first_name" name="first_name" value="<?= old('first_name', session()->get('first_name')); ?>">

		<label for="email">Email</label>
		<input type="email" id="email" name="email" value="<?= old('email', session()->get('email')); ?>">

		<label for="phone_number">Numéro de téléphone</label>
		<input type="text" id="phone_number" name="phone_number" value="<?= old('phone_number', session()->get('phone_number')); ?>">

		<label for="newsletter">
            <input type="checkbox" id="newsletter" name="newsletter" <?= session()->get('newsletter') ? 'checked' : ''; ?>>
            Inscrivez-vous à la newsletter
        </label>

		<button type="submit">Enregistrer les modifications</button>
	</form>
</div>