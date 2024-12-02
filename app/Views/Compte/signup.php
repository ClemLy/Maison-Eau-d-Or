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

input[type="text"],
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

input[type="text"]:focus,
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

/* Conteneur des erreurs */
.form-error {
    background-color: #ffe6e6; /* Fond rouge clair pour attirer l'attention */
    border: 1px solid #ff4d4d; /* Bordure rouge */
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    color: #a94442; /* Rouge foncé pour le texte */
    font-size: 14px;
	font-weight: bold;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Liste des erreurs */
.error-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

/* Chaque élément d'erreur */
.error-list li {
    margin-bottom: 5px;
    line-height: 1.5;
}

/* Icône ou décoration pour chaque erreur */
.error-list li::before {
    content: '⚠️'; /* Icône d'avertissement */
    margin-right: 10px;
    color: #ff4d4d;
}
</style>

<div class="div-login">
	<div class="container">
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
				<label for="nom_user">Nom</label>
				<input type="text" name="nom_user" id="nom_user" value="<?= set_value('nom_user') ?>" required>
			</div>

			<div class="form-group">
				<label for="prenom_user">Prenom</label>
				<input type="text" name="prenom_user" id="prenom_user" value="<?= set_value('prenom_user') ?>" required>
			</div>

			<div class="form-group">
				<label for="email_user">Adresse email</label>
				<input type="email_user" name="email_user" id="email_user" value="<?= set_value('email_user') ?>" required>
			</div>

			<div class="form-group">
				<label for="password">Mot de passe</label>
				<input type="password" name="password" id="password" required>
			</div>

			<div class="form-group">
				<label for="confirmpassword">Confirmer le mot de passe</label>
				<input type="password" name="confirmpassword" id="confirmpassword" required>
			</div>

			<button type="submit">S'inscrire</button>
		</form>
		
		<p>Vous avez déjà un compte ? <a href="<?= site_url('signin') ?>">Connectez-vous ici</a></p>
	</div>
</div>