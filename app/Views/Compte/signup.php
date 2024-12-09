<div class="form-container">
	<h2>Inscription</h2>

	<?php if(isset($validation)): ?>
		<div class="form-error">
			<ul class="error-list">
				<?php foreach($validation->getErrors() as $error): ?>
					<li><?= esc($error) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<form action="<?= site_url('signup/store') ?>" method="post">
		<div class="form-group">
			<label for="last_name">Nom (*)</label>
			<input type="text" name="last_name" id="last_name" value="<?= set_value('last_name') ?>" required>
		</div>

		<div class="form-group">
			<label for="first_name">Prénom (*)</label>
			<input type="text" name="first_name" id="first_name" value="<?= set_value('first_name') ?>" required>
		</div>

		<div class="form-group">
			<label for="email">Adresse email (*)</label>
			<input type="email" name="email" id="email" value="<?= set_value('email') ?>" placeholder="Ex : monemail@gmail.com" required>
		</div>

		<div class="form-group">
			<label for="phone_number">Numéro de téléphone</label>
			<input type="text" name="phone_number" id="phone_number" value="<?= set_value('phone_number') ?>" placeholder="Ex : 0601020304">
		</div>

		<div class="form-group">
			<label for="password">Mot de passe (*)</label>
			<input type="password" name="password" id="password" required>
		</div>

		<div class="form-group">
			<label for="confirmpassword">Confirmer le mot de passe (*)</label>
			<input type="password" name="confirmpassword" id="confirmpassword" required>
		</div>

		<button type="submit">S'inscrire</button>
	</form>
	
	<p>* Champ Obligatoire
	<p>Vous avez déjà un compte ? <a href="<?= site_url('signin') ?>">Connectez-vous ici</a></p>
</div>