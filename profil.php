<?php
require_once('inc/init.inc.php');

// debug($_SESSION);
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
// debug($_SESSION['membre']);

// Traitement pour rediriger l'utilisateur s'il n'est pas connecté
if(!userConnecte()){
    header('location:connexion.php');
}

$page = 'Profil';
extract($_SESSION['membre']);
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>
<!-- Contenu HTML -->
<h1>Profil</h1>
<div class="container">
    <div class="profil">
        <p class="profil_bienvenue"> Bienvenue, <?= $pseudo ?></p><br>

    <div class="profil_img">
        <img src="<?= RACINE_SITE ?>img/default.jpg">
    </div>

    <div class="profil_infos col-md-5">
        <ul >
            <li class="list-group-item">Pseudo : <b><?= $pseudo ?></b></li>
            <li class="list-group-item">Prénom : <b><?= $prenom ?></b></li>
            <li class="list-group-item">Nom : <b><?= $nom ?></b></li>
            <li class="list-group-item">Email : <b><?= $email ?></b></li>
            <li class="list-group-item">Téléphone : <b><?= $telephone ?></b></li>
        </ul>
    </div>
    </div>
</div>



<?php
require_once('inc/footer.inc.php');
?>
