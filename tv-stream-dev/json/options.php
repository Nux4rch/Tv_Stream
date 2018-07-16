<?php
	session_start();
	header('Content-Type: application/json');
	if(!isset($_SESSION['name'])){$err='{"err":"not log"}';}//nop
	else // is adm
	{
		include('../asset/bdd.php');
		if(isset($_GET["djbadge"]))
		{
			$reponse = $bdd->prepare('UPDATE `pos` SET `actived`=0 WHERE `uname`=:name');
			$reponse->bindValue(':name',$_SESSION['name'],PDO::PARAM_STR);
			$reponse->execute();
			$reponse->closeCursor();
			
			$reponse = $bdd->prepare('UPDATE `pos` SET `actived`=1 WHERE `uname`=:name and `icoid`=:el');
			$reponse->bindValue(':name',$_SESSION['name'],PDO::PARAM_STR);
			$reponse->bindValue(':el',htmlentities($_GET["djbadge"]),PDO::PARAM_INT);
			$reponse->execute();
			$reponse->closeCursor();
			$err='{"err":"null"}';
		}
		else{$err='{"err":"no selected"}';}
	}
	if(!isset($_GET["r"])){header('Location: /');}
	echo $err;