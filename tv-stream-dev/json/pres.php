<?php
	include('../asset/bdd.php');
	header('Content-Type: application/json');

	$pres=array();
	$reponse = $bdd->prepare('SELECT * FROM `catwoman` order by `id`');
	$reponse->execute();
	while ($donnees = $reponse->fetch())
	{
		$artl=array();
		$l= $bdd->prepare('SELECT * FROM `posterieur` WHERE `cid`=:id order by `id` DESC');
		$l->bindValue(':id',$donnees['id'],PDO::PARAM_INT);
		$l->execute();
		while ($ar = $l->fetch()){array_push($artl,array("title"=>$ar['title'],"txt"=>$ar['txt']));}
		array_push($pres,array("id"=>$donnees['id'],"title"=>$donnees['title'],"view"=>$donnees['view'],"ar"=>$artl));
		$l->closeCursor();
	}
	$reponse->closeCursor();
	echo json_encode($pres);
