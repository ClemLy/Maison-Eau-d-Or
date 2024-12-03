<h1>Mon Compte</h1>
<p>Bienvenue, <?= esc(session()->get('first_name')); ?> <?= esc(session()->get('last_name')); ?> !</p>

<ul>
	<li>Email : <?= esc(session()->get('email')); ?></li>

	<?php if (session()->get('phone_number')) : ?>
		<li>Téléphone : <?= esc(session()->get('phone_number')); ?></li>
	<?php endif; ?>
	
	<li><a href="<?= site_url('account/update'); ?>">Modifier mes informations</a></li>
	<li><a href="<?= site_url('account/delete'); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">Supprimer mon compte</a></li>
	<li><a href="<?= site_url('logout'); ?>">Se déconnecter</a></li>
</ul>

<?php if (session()->getFlashdata('success')) : ?>
	<p style="color: green;"><?= session()->getFlashdata('success'); ?></p>
<?php endif; ?>