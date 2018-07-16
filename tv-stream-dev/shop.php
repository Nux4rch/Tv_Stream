<?php
	session_start();
	include('asset/bdd.php');
	$title="Shop";
	//achat ?
	$msg="";
	if(isset($_GET["ico_id"]))
	{
		if(isset($_SESSION['name']))
		{
			
			$reponse = $bdd->prepare('SELECT `icoid` FROM `pos` WHERE `uname`=:name and `icoid`=:id');
			$reponse->bindValue(':name',htmlentities($_SESSION['name']),PDO::PARAM_STR);
			$reponse->bindValue(':id',intval($_GET["ico_id"]),PDO::PARAM_INT);
			$reponse->execute();
			if($donnees = $reponse->fetch()){$ach=true;}
			$reponse->closeCursor();
			if(!isset($ach))
			{
				//pts user
				$reponse = $bdd->prepare('SELECT * FROM `user` WHERE `pseudale`=:name');
				$reponse->bindValue(':name',htmlentities($_SESSION['name']),PDO::PARAM_STR);
				$reponse->execute();
				$donnees = $reponse->fetch();
				$pts = $donnees['pts'];
				$reponse->closeCursor();
				//recup prix
				$reponse = $bdd->prepare('SELECT `money` FROM `items` WHERE `id`=:id');
				$reponse->bindValue(':id',$_GET["ico_id"],PDO::PARAM_INT);
				$reponse->execute();
				$donnees = $reponse->fetch();
				$pr = $donnees['money'];//prix
				//$pr = 20;//prix
				$reponse->closeCursor();
				if($pts >= $pr)
				{
					$_SESSION['pts']-=$pr;
					$reponse = $bdd->prepare('UPDATE `user` SET `pts`=`pts` - :p WHERE `pseudale`=:name');
					$reponse->bindValue(':name',htmlentities($_SESSION['name']),PDO::PARAM_STR);
					$reponse->bindValue(':p',$pr,PDO::PARAM_INT);
					$reponse->execute();
					$reponse->closeCursor();
					$reponse = $bdd->prepare('INSERT INTO `pos`(`uname`, `icoid`) VALUES (:name,:id)');
					$reponse->bindValue(':name',htmlentities($_SESSION['name']),PDO::PARAM_STR);
					$reponse->bindValue(':id',$_GET["ico_id"],PDO::PARAM_INT);
					$reponse->execute();
					$reponse->closeCursor();
				}
				else{$msg="no pts";}
			}
			else{$msg="article deja acheter";}
		}
		else{$msg="not connected";}
	}
	//userinfo
	if( isset($_SESSION['id']) )
	{
		$userinfo='			<li><a>'.$_SESSION['name']."</a></li>\n";
		//$userinfo.='		<li><a href="https://api.twitch.tv/kraken/oauth2/revoke?client_id='.$api_id.'&token='.$token.'">logout</a></li>'."\n";
	}
	else{$userinfo='			<li><a href="https://api.twitch.tv/kraken/oauth2/authorize?client_id='.$api_id.'&redirect_uri='.$api_root.'&response_type=token&scope=user_read+chat_login+channel_subscriptions">twitch login</a></li>'."\n";}
	//shop items
	$items="";
	$reponse = $bdd->prepare('SELECT * FROM items');
	$reponse->execute();
	while ($donnees = $reponse->fetch())
	{$items.='					<div class="product"><a href="?ico_id='.$donnees['id'].'"><img alt="'.$donnees['money'].' points" src="/asset/img/djbadge/'.$donnees['url'].'"><br>"'.$donnees['name'].'" a '.$donnees['money']."points</a></div>\n";}
	$reponse->closeCursor();
	//$badges & $badges_act
	if(isset($_SESSION['name']))
	{
		$reponse = $bdd->prepare('SELECT `icoid`,`actived`,`url`,`name` FROM `items`,`pos` WHERE `pos`.`icoid`=`items`.`id` and `uname`=:name');
		$reponse->bindValue(':name',$_SESSION['name'],PDO::PARAM_STR);
		$reponse->execute();
		while ($donnees = $reponse->fetch())
		{	if(!isset($badges)){$badges="";}
			$badges.='<a href="/json/options.php?djbadge='.$donnees['icoid'].'"><img src="/asset/img/djbadge/'.$donnees['url'].'" alt="'.$donnees['name'].'" height="30em"></a>'."\n";
			if($donnees['actived']){$badges_act='<li id="badges-selected"><img src="/asset/img/djbadge/'.$donnees['url'].'" alt="'.$donnees['name'].'"width="40%"></li>';}
		}
		$reponse->closeCursor();
	}	
include('view/shop.php');				
