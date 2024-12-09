<div class="page-container">
	<h1>Contactez-Nous</h1>

	<?php if (session()->getFlashdata('success')): ?>
		<div class="alert alert-success">
			<?= session()->getFlashdata('success') ?>
		</div>
	<?php elseif (session()->getFlashdata('error')): ?>
		<div class="alert alert-danger">
			<?= session()->getFlashdata('error') ?>
		</div>
	<?php endif; ?>

	<div class="page-content">
		<form action="<?= site_url('contact/send') ?>" method="post">
			<div class="form-group mb-3">
				<label for="first_name">Prénom</label>
				<input type="text" id="first_name" name="first_name" class="form-control" value="<?= old('first_name') ?>" required>
			</div>

			<div class="form-group mb-3">
				<label for="last_name">Nom</label>
				<input type="text" id="last_name" name="last_name" class="form-control" value="<?= old('last_name') ?>" required>
			</div>

			<div class="form-group mb-3">
				<label for="email">Adresse Email</label>
				<input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
			</div>

			<div class="form-group mb-3">
				<label for="message">Message</label>
				<textarea id="message" name="message" class="form-control" rows="5" required><?= old('message') ?></textarea>
			</div>

			<button type="submit" class="btn btn-primary w-100">Envoyer</button>
		</form>

		<?php if (isset($validation)): ?>
			<div class="alert alert-danger mt-3">
				<?= $validation->listErrors() ?>
			</div>
		<?php endif; ?>

	</div>
</div>