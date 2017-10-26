<?php
require('../inc/init.inc.php');
if(!empty($_POST)){
// Attention à personnaliser pour chaque page
    $url_photo = '';
    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        $url_photo = $_POST['photo_actuelle'];
        //echo $url_photo;
    }
    if(!empty($_FILES['photo']['name'])){ // Si une photo est uploadé
        echo '<pre>'; print_r($_FILES); echo '</pre>';

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
    $resultat = $pdo -> exec("UPDATE annonce set titre = '$_POST[titre]', description_courte = '$_POST[description_courte]', description_longue = '$_POST[description_longue]', prix = '$_POST[prix]', photo = '$url_photo', pays = '$_POST[pays]', ville = '$_POST[ville]', adresse = '$_POST[adresse]', cp = '$_POST[cp]' WHERE id_annonce = '$_GET[id_annonce]'");

    //header('location:' . URL . 'backoffice/gestion_annonces.php');
    $msg .= '<div class="alert alert-success" > L\'annonce a bien été modifié </div>';

}

$resultat = $pdo -> query("SELECT * FROM annonce");

include('../inc/header.inc.php');
include('../inc/nav.inc.php');

$contenu ='';
$contenu .= 'Nombre de résultats : '.$resultat -> rowCount(). '<br><hr>';

$contenu .= '<div class="container-fluid">';
    $contenu .= '<div class="col-md-8">';
        $contenu .= '<table class="table table-bordered">'; // création du tableau HTML
            $contenu .= '<tr>'; // création de la ligne de titre

                for($i = 0; $i < $resultat -> columnCount(); $i++){
                    $colonne = $resultat -> getColumnMeta($i);
                    if($colonne['name'] != 'mdp')
                    {
                    $contenu .= '<th>'. $colonne['name'] . '</th>';
                    }
                }
                $contenu .= '<th colspan="3">Actions</th>';
            $contenu .= '</tr>';

    while($gestion_annonce = $resultat -> fetch(PDO::FETCH_ASSOC)){
        $contenu .= '<tr>';
        foreach($gestion_annonce as $indices => $informations){
            if($indices == 'photo'){
                $contenu .= '<td><img src="'  . $informations . '"height="90" width="90"></td>';
            }
            else{
                $contenu .= '<td>' . $informations . '</td>';
            }

        }

    $contenu .= '<td class = "modif"><a href="?action=modification&id_annonce=' . $gestion_annonce['id_annonce']  . '"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button></a></td>';

    $contenu .= '<td class = "modif"><a href="modif_membres.php"><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus" aria-hidden="true"></button></a></td>';

    $contenu .= '<td class="supr"><a href="supprim_annonces.php?id='. $gestion_annonce['id_annonce'] .'"><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></a></td>';

    $contenu .= '</tr>';
    }


    $contenu .= '<table/>';
    $contenu .= '<komvd/div>';
    $contenu .= '</div>';


?>

<h1>Gestion des annonces</h1>



<?php
    echo $contenu;
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    if(isset($_GET['id_annonce']))
    {
        $resultat = $pdo->query("SELECT * FROM annonce WHERE id_annonce = '$_GET[id_annonce]'");
        $annonce_actuel = $resultat->fetch(PDO::FETCH_ASSOC);
    }

    $id_annonce = (isset($annonce_actuel)) ? $annonce_actuel['id_annonce'] : '';
    $titre = (isset($annonce_actuel)) ? $annonce_actuel['titre'] : ''; // meme finalité que le if/else du dessus mais de manière simplifiée
    $description_courte = (isset($annonce_actuel)) ? $annonce_actuel['description_courte'] : '';
    $description_longue = (isset($annonce_actuel)) ? $annonce_actuel['description_longue'] : '';
    $prix = (isset($annonce_actuel)) ? $annonce_actuel['prix'] : '';
    $photo = (isset($annonce_actuel)) ? $annonce_actuel['photo'] : '';
    $pays = (isset($annonce_actuel)) ? $annonce_actuel['pays'] : '';
    $ville = (isset($annonce_actuel)) ? $annonce_actuel['ville'] : '';
    $adresse = (isset($annonce_actuel)) ? $annonce_actuel['adresse'] : '';
    $cp = (isset($annonce_actuel)) ? $annonce_actuel['cp'] : '';



?>
<?= $msg ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                        <input type="hidden" class="form-control" name="id_annonce" id ="id_annonce" value="<?= $id_annonce ?>">


                    <div class="form-group">
                        <label for="titre">Titre </label>
                        <input type="text" class="form-control" name="titre" id ="titre" value="<?= $titre ?>">
                    </div>

                    <div class="form-group">
                        <label for="description_courte">Description courte</label>
                        <input type="text" class="form-control" name="description_courte" id ="description_courte" value="<?= $description_courte ?>">
                    </div>

                    <div class="form-group">
                        <label for="description_longue">Description longue</label>
                        <input type="text" class="form-control" name="description_longue" id ="description_longue" value="<?= $description_longue ?>">
                    </div>

                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" class="form-control" name="prix" id ="prix" value="<?= $prix ?>">
                    </div>
                    <div class="form-group">
                        <label>Photo</label>
    		             <input id="photo" name="photo" type="file" class="file form-control" data-show-preview="false" value="<?= $photo ?>">
    		        </div>
                        <img src="<?= $photo ?>" height="100px"/>
                        <input type="hidden" name="photo_actuelle" value="<?= $photo ?>" />

            </div>
            <div class="col-md-6 ">
                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" class="form-control" name="pays" id ="pays" value="<?= $pays ?>">
                </div>

                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" name="ville" id ="ville" value="<?= $ville ?>">
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" name="adresse" id ="adresse" value="<?= $adresse ?>">
                </div>
                <div class="form-group">
                    <label for="cp">Code postal</label>
                    <input type="text" class="form-control" name="cp" id ="cp" value="<?= $cp ?>">
                </div>


            </div>
            <input type="submit" class="form-control" name=""  value="Modifier">
        </form>
    </div>
<?php
}

 include('../inc/footer.inc.php');
