<?php

// connexion à la session
session_start();

// connexion BDD
$hote='localhost';
$bdd='projet_annonceo';
$utilisateur='root';
$passe='';

$pdo = new PDO('mysql:host='.$hote.';dbname='.$bdd, $utilisateur, $passe);
$pdo -> exec("SET NAMES utf8");

// constante pour les chemins
define('RACINE_SITE', '/projet_annonceo/');
define('URL', 'http://localhost/projet_annonceo/');
//echo URL;

// déclaration variable msg pour afficher msg d'erreur
$msg='';

require('fonctions.inc.php');
