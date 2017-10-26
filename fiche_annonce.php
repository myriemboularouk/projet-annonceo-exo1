<?php
require('inc/init.inc.php');


// Attention à personnaliser pour chaque page

// $resultat = $pdoCV -> query("SELECT * FROM );
// $ligne_utilisateur = $resultat -> fetch(PDO::FETCH_ASSOC);

include('inc/header.inc.php');
include('inc/nav.inc.php');

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){

	$resultat = $pdo -> prepare("SELECT * FROM annonce WHERE id_annonce = :id_annonce");
	$resultat -> bindParam(':id_annonce', $_GET['id'], PDO::PARAM_INT); // au marqueur .. je met la valeur .. c'est du type ..
	$resultat -> execute();

	if($resultat -> rowCount() > 0){ // le produit existe bien
		$fiche_annonce = $resultat -> fetch(PDO::FETCH_ASSOC);
		extract($fiche_annonce);
		// debug($produit);

	}
	else{ //le produit n'existe pas : REDIRECTION !

		header('location:boutique.php');
	}
} //Il n'y a pas d'ID dans l'URL ou vide, ou pas numérique : REDIRECTION
else{
	header('location:boutique.php');
}
?>
<h1>Profil</h1>
<div class="container-fluid">
        <h2 class="profil_bienvenue"> Annonce, <?= $titre ?></h2><br>

    <div class="profil_infos col-md-4"></div>
    <div class="profil_infos col-md-4">
        <ul >
            <li class="list-group-item">Photo : <b><img src="<?= $photo ?>"></b></li>
            <li class="list-group-item">Titre : <b><?= $titre ?></b></li>
            <li class="list-group-item">Description : <b><?= $description_longue ?></b></li>
            <li class="list-group-item">Prix : <b><?= $prix ?></b></li>
        </ul>
    </div>
    <span><a href="accueil.php">Retour aux annonces</a></span>
</div>







<?php include('inc/footer.inc.php'); ?>
