<h1>Mon Compte</h1>
<p>Bienvenue, <?= esc(session()->get('prenom_user')); ?> <?= esc(session()->get('nom_user')); ?> !</p>

<ul>
	<li>Email : <?= esc(session()->get('email_user')); ?></li>
	<li><a href="<?= site_url('compte/update'); ?>">Modifier mes informations</a></li>
	<li><a href="<?= site_url('compte/delete'); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">Supprimer mon compte</a></li>
	<li><a href="<?= site_url('logout'); ?>">Se déconnecter</a></li>
</ul>

<?php if (session()->getFlashdata('success')) : ?>
	<p style="color: green;"><?= session()->getFlashdata('success'); ?></p>
<?php endif; ?>