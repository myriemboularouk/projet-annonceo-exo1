<?php
require('inc/init.inc.php');


// Attention à personnaliser pour chaque page

$resultat = $pdo -> query("SELECT titre FROM categorie");
$annonce_categorie = $resultat -> fetchAll(PDO::FETCH_ASSOC);

include('inc/header.inc.php');
include('inc/nav.inc.php');

if(!empty($_POST)){
    if(!empty($_FILES['photo']['name'])){ // Si une photo est uploadé
        //echo '<pre>'; print_r($_FILES); echo '</pre>';
        $nom_photo = time(). '_' . $_FILES['photo']['name']; // Si la photo est nommé tshirt.jpg, on la renomme : XX21-1543234454_tshirt.jpg pour éviter les doublons possible sur le serveur

        $chemin_photo = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'photo/' . $nom_photo;
        //echo $chemin_photo;
        $url_photo = URL . 'photo/' . $nom_photo;
        // chemin : c://xampp/htdocs/PHP/site/photo/XX21-1543234454_tshirt.jpg

        $ext = array('image/png', 'image/jpeg', 'image/gif');

        if(!in_array($_FILES['photo']['type'], $ext)){
            $msg .= '<div class="erreur"> Images autorisées : PNG, JPG, JPEG et GIF </div>'; // Si le fichier uploadé ne correspond pas aux extenstions autorisées (ici, PNG, JPG, JPEG et GIF ) alors on affiche un message d'erreur.
        }

        if($_FILES['photo']['size'] > 2000000){
            $msg .= '<div class="erreur"> Images : 2Mo maximum autorisé </div>'; // SI la photo uploadée est trop volumineurse (ici 2Mo max) alors on met un message d'erreur.
            // PAr défaut XAMPP autorise 2.5Mo. Voir dans php.ini, rechercher max_filesize=2.5M
        }

        if(empty($msg) && $_FILES['photo']['error'] == 0){
            copy($_FILES['photo']['tmp_name'], $chemin_photo); // On enregistre la photo sur le serveur. Dans les faits, on la copier depuis son emplacement temporaire et on la colle dans so emplacement définitif.
        }
    }

    if(empty($_POST['titre']) && empty($_POST['description_courte']) && empty($_POST['prix'])){
        $msg .= '<div class="erreur"> Veuillez renseigner un titre, une description courte et un prix </div>';
    }

    if(empty($msg)){

        $resultat = $pdo -> prepare("INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, date_enregistrement) VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, NOW()) ");

        $resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
        $resultat -> bindParam(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
        $resultat -> bindParam(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
        $resultat -> bindParam(':prix', $_POST['prix'], PDO::PARAM_INT);
        $resultat -> bindParam(':photo', $url_photo, PDO::PARAM_STR);
        $resultat -> bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
        $resultat -> bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
        $resultat -> bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $resultat -> bindParam(':cp', $_POST['cp'], PDO::PARAM_INT);


            $resultat -> execute();
            $msg .= '<div class="alert alert-success">Annonce publiée!</div>';
            // echo '<pre>'; print_r($_POST); echo '</pre>';

    }
}// fin du if !empty($_POST)

?>

<h1>Déposer une annonce</h1>
<?= $msg ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-0">
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                    <!-- <input type="hidden" class="form-control" name="id_categorie" id ="id_categorie" value=""> -->

                <div class="form-group">
                    <label for="titre">Titre </label>
                    <input type="text" class="form-control" name="titre" id ="titre" value="">
                </div>

                <div class="form-group">
                    <label for="description_courte">Description courte</label>
                    <textarea  class="form-control" name="description_courte" id ="description_courte" value=""></textarea>
                </div>
                <div class="form-group">
                    <label for="description_longue">Description longue</label>
                    <textarea  class="form-control" name="description_longue" id ="description_longue" value=""></textarea>
                </div>

                <div class="form-group">
                    <label for="prix">Prix</label>
                    <input type="text" class="form-control" name="prix" id ="prix" value="">
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select class="form-control" name="categorie">
                        <?php foreach ($annonce_categorie as $value): ?>
                             <?= '<option>'. $value['titre'] .'</option>'?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6 offset-md-0">
                <div class="form-group">
                    <label>Photo</label>
		             <input id="photo" name="photo" type="file" class="file form-control" data-show-preview="false">
		        </div>
                <img src="<?= RACINE_SITE ?>photo/<?= $photo ?>" height="100px"/>
                <input type="hidden" name="photo_actuelle" value="" />
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" name="ville" id ="ville" value="">
                </div>
                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" class="form-control" name="pays" id ="pays" value="">
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <textarea  class="form-control" name="adresse" id ="adresse" value=""></textarea>
                </div>
                <div class="form-group">
                    <label for="cp">Code postal</label>
                    <input type="text" class="form-control" name="cp" id ="cp" value="">
                </div>
            </div>
        </div>
        <input type="submit" class="form-control btn btn-info" name=""  value="Déposer">
    </form>
</div>

        <!-- Contenu de la page -->

<?php include('inc/footer.inc.php'); ?>
