<?php
require('inc/init.inc.php');


// Attention à personnaliser pour chaque page

// $resultat = $pdoCV -> query("SELECT * FROM );
// $ligne_utilisateur = $resultat -> fetch(PDO::FETCH_ASSOC);

include('inc/header.inc.php');

if(!empty($_POST)){

    // --2. Afficher avec print_r
    // debug($_POST);

    // --3. Contrôle sur les champs (pseudo et mdp)
        echo '<pre>'; print_r($_POST); echo '</pre>';
        //--------------------------------------------------------------------------------------------------------------
        //--> Vérification pseudo :
        $verif_pseudo = preg_match('#^([a-zA-Z0-9._-]{3,20})$#', $_POST['pseudo']); // cette fonction me permet de mettre une règle en place pour les caractères autorisés :
            // arg1 : REGEX - EXPRESSIONS REGULIERES
            // arg2 : la chaînes de caractère (sur laquelle on va vérifier)
            // Retour : TRUE (si OK) - FALSE (si pas OK )

        if(!empty($_POST['pseudo'])){
            if(!$verif_pseudo){ // = ($verif_pseudo == FALSE ou $verif_pseudo != TRUE) Si verif_pseudo nous retourne false
                $msg .= '<div class="erreur"> Votre pseudo doit être compris entre 3 et 20 caractères, et ne doit pas comporter de caractères spéciaux (sauf ._-) </div>';
            }
        }
        else{
            $msg .= '<div class="erreur"> Veuillez renseigner un pseudo </div>';
        }


            //--------------------------------------------------------------------------------------------------------------
            //--> Vérification du mot de passe :
        $verif_pwd = preg_match('#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$#', $_POST['mdp']); // 8 caractères min, 20max, au moins un chiffre, au moins une maj

        if(!empty($_POST['mdp'])){
            if(!$verif_pwd){
                $msg .= '<div class="erreur"> Votre mot de passe doit comporter entre 8 et 20 caractères et doit contenir au moins un chiffre et une majuscule.</div>';
            }
        }
        else{
            $msg .= '<div class="erreur"> Veuillez renseigner un mot de passe.</div>';
        }


            //--------------------------------------------------------------------------------------------------------------------
            //--> Vérification de l'email :
        $verif_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // vérifie que le format de l'email est OK (retourne TRUE si OK, FALSE si pas OK)

        //ex: yakine.hamida@gmail.com
        $pos = strpos($_POST['email'], '@'); // la position de @
        $ext = substr($_POST['email'], $pos +1); // 'gmail.com'

        $ext_non_autorisees = array('wimsg.com', 'yopmail.com', 'mailinator.com', 'tafmail.com', 'mvrht.net');

        if(!empty($_POST['email'])){
            if(!$verif_email || in_array($ext, $ext_non_autorisees)){
                $msg .= '<div class="erreur"> Veuillez saisir un email valide.</div>';
            }
        }
        else{
                $msg .='<div class="erreur"> Veuillez renseigner un email.</div>';
        }

        // A ce stade, si notre variable $msg est encore vide, cela signifie qu'il n'y a pas d'erreur au moins sur email, pseudo et MDP (Pensez à faire les verifs des autres champs)

        if(empty($msg)){ // Tout est ok (car pas de message d'erreur)
            // --4. Enregistrer l'utilisateur
                //--> Pseudo dispo? / Email dispo ?
                $resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
                $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $resultat -> execute();

                if($resultat -> rowCount() > 0){ // signifie que le pseudo est déja utilisé puisque supérieur à 0 donc pas nul
                    // Nous aurion pu lui proposer 2/3 variante de son pseudo, en ayant vérifié qu'ils sont disponible.
                    $msg .= '<div class="erreur"> Le pseudo '. $_POST['pseudo'].' n\'est pas dispobible, veuillez choisir un autre pseudo.</div>';
                }
                else{ // OK le pseudo est dispo; on va pourvoir enregistrer le membre dans la BDD , nous devrions également vérifier la disponibilité de l'email
                    //--> crypte le MDP
                    $mdp = md5($_POST['mdp']); // md5() va crypté le mdp selon le hashage 64octet

                    //--> Requête INSERT pour enregistrer un nouvel utilisateur
                    $resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, NOW())");
                    $resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                    $resultat -> bindParam(':mdp', $mdp, PDO::PARAM_STR); //$mdp : la variable $mdp où le mdp est crypté avec md5()
                    $resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
                    $resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                    $resultat -> bindParam(':telephone', $_POST['telephone'], PDO::PARAM_STR);
                    $resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                    $resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR);

                    //--> Redirection vers la page de connexion
                    if($resultat -> execute()){ // Si la requête est OK
                        header('location:inscription.php');
                    }
                }//fin du else rowCount()

        }// fin du if(empty($msg))

}// fin du if !empty($_POST)

// Pour maintenir les infos dans le formulaire en cas d'erreur, on doit définir une variable pour chaque champs
if(isset($_POST['pseudo'])){
    $pseudo = $_POST['pseudo'];
}
else{
    $pseudo = '';
}
$nom = (isset($_POST['nom'])) ? $_POST['nom'] : ''; // meme finalité que le if/else du dessus mais de manière simplifiée
$prenom = (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$ville = (isset($_POST['ville'])) ? $_POST['ville'] : '';
$civilite = (isset($_POST['civilite'])) ? $_POST['civilite'] : '';
$adresse = (isset($_POST['telephone'])) ? $_POST['telephone'] : '';


include('inc/nav.inc.php');
?>

<?= $msg ?>
<form class="col-md-5 col-md-offset-2" method="post" action="">
  <div class="form-group">
    <label for="pseudo">Pseudo :</label>
    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="pseudo">
  </div>
  <div class="form-group">
    <label for="mdp">Mot de passe</label>
    <input type="text" class="form-control" id="mdp" name="mdp">
  </div>
  <div class="form-group">
    <label for="nom">Nom</label>
    <input type="text" class="form-control" id="nom" name="nom">
  </div>
  <div class="form-group">
    <label for="prenom">Prenom</label>
    <input type="text" class="form-control" id="prenom" name="prenom">
  </div>
  <div class="form-group">
    <label for="telephone">Téléphone</label>
    <input type="text" class="form-control" id="telephone" name="telephone">
  </div>
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" class="form-control" id="email" name="email">
  </div>
  <div class="form-group">
    <label for="civilite">Civilité</label>
    <select class="form-control" name="civilite">
        <option value="m">Homme</option>
        <option value="f">Femme</option>
    </select>
  </div>


  <button type="submit" class="btn btn-default">Submit</button>
</form>

<?php include('inc/footer.inc.php'); ?>
