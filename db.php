<?php
try{
    $bdd = new PDO('mysql:dbname=jeu-combat; host=127.0.0.1; charset=utf8','root','');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (Exception $exception){
    die('Il y a une erreur :'.$exception->getMessage());
}
session_start();
