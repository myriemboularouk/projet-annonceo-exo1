<?php

$hote='localhost';
$bdd='projet_annonceo';
$utilisateur='root';
$passe='';

$pdo = new PDO('mysql:host='.$hote.';dbname='.$bdd, $utilisateur, $passe);
$pdo -> exec("SET NAMES utf8");
