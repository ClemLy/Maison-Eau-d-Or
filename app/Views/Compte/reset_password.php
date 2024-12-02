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

.form-group {
	margin-bottom: 15px;
}

label {
	font-weight: 500;
	color: #333;
}

input[type="password"],input[type="confirm_password"] {
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

input[type="password"]:focus, input[type="confirm_password"]:focus {
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

a.return-link {
    display: block;
    margin-top: 20px;
    text-align: center;
    font-size: 0.95rem;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

a.return-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-size: 16px;
	font-weight: bold;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

</style>

<div class="div-login">
	<div class="container">
		<h2>Réinitialisation du Mot de Passe</h2>

		<?php if (session()->getFlashdata('success-reset')): ?>
			<div class="alert alert-success">
				<?= session()->getFlashdata('success-reset') ?>
			</div>
		<?php elseif (session()->getFlashdata('error-reset')): ?>
			<div class="alert alert-danger">
				<?= session()->getFlashdata('error-reset') ?>
			</div>
		<?php endif; ?>

		<form action="<?= site_url('reset-password/update') ?>" method="post">
			<input type="hidden" name="token" value="<?= $token ?>">

			<div class="form-group">
				<label for="password">Nouveau Mot de Passe</label>
				<input type="password" name="password" id="password" required>
			</div>

			<div class="form-group">
				<label for="confirm_password">Confirmer Mot de Passe</label>
				<input type="password" name="confirm_password" id="confirm_password" required>
			</div>
			
			<button type="submit">Réinitialiser Mot de Passe</button>
		</form>

		<a href="/sigin">Retour à la connexion</a>
	</div>
</div>