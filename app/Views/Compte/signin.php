<div class="form-container">
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