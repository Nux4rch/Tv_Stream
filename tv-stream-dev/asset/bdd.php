<?php
try
{
	// On se connecte à MySQL
	$bdd = new PDO('mysql:host=localhost;dbname=tv-stream', 'tv-stream', 'mDUTkaqJVduKlNIL', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}   //conection      host(localhost),base de donee,user,mdp
catch(Exception $e)
{
	// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}
// Si tout va bien, on peut continuer