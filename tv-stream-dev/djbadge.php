<?php
	include('asset/bdd.php');
	header('Content-Type: application/json');
	$pres=array();
	$reponse = $bdd->prepare('SELECT `actived`,`name`,`url` FROM `items`,`pos` WHERE `pos`.`icoid`=`items`.`id`and `uname`=:ps');
	$reponse->bindValue(':ps',htmlspecialchars($_GET["u"]),PDO::PARAM_STR);
	$reponse->execute();
	while ($donnees = $reponse->fetch())
	{ if($donnees['actived']==true){$pres=array("name"=>$donnees['name'],"url"=>$donnees['url']);} }
	$reponse->closeCursor();
	echo json_encode($pres);
