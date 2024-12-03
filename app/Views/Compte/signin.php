<style>
body,html{
	overflow-y: hidden;
}
.div-login {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; 
    background-color: #f1f1f1;

}

.container {
    max-width: 400px;
    padding: 20px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.container h2 {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

form {
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: 500;
    color: #333;
}

input[type="email_user"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    font-size: 0.9rem;
    border: 1px solid #ced4da;
    border-radius: 5px;
    background-color: #fff;
    color: #495057;
    outline: none;
    transition: border-color 0.3s ease;
}

input[type="email_user"]:focus,
input[type="password"]:focus {
    border-color: #80bdff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

button[type="submit"] {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.container p {
    text-align: center;
    font-size: 0.85rem;
    margin-top: 15px;
    color: #495057;
}

.container p a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.container p a:hover {
    color: #0056b3;
}

</style>
<div class="div-login">
	<div class="container">
		<h2>Connexion</h2>


		<!-- Affichage des messages de succès ou d'erreur -->
		<?php if(session()->getFlashdata('msg')): ?>
			<div class="alert alert-warning">
				<?= session()->getFlashdata('msg') ?>
			</div>
		<?php endif; ?>

		<?php if(session()->getFlashdata('success')): ?>
			<div class="alert alert-success">
				<?= session()->getFlashdata('success') ?>
			</div>
		<?php endif; ?>

		<?php if(session()->getFlashdata('error')): ?>
			<div class="alert alert-danger">
				<?= session()->getFlashdata('error') ?>
			</div>
		<?php endif; ?>


		<!-- Formulaire de connexion -->
		<form action="<?= site_url('signin/auth') ?>" method="post">
			<div class="form-group">
				<label for="email">Adresse email</label>
				<input type="email" name="email" id="email" required>
			</div>

			<div class="form-group">
				<label for="password">Mot de passe</label>
				<input type="password" name="password" id="password" required>
			</div>

			<div class="form-group">
				<label for="remember">
					<input type="checkbox" name="remember" id="remember" value="1"> Se souvenir de moi
				</label>
			</div>

			<button type="submit">Se connecter</button>
		</form>
		
		<p>Pas encore de compte ? <a href="<?= site_url('signup') ?>">Inscrivez-vous ici</a></p>
		<p><a href="<?= site_url('forgot-password') ?>">Mot de passe oublié ?</a></p>
	</div>
</div>