<?php
	session_start();
	include('asset/twitch.php');
	include('asset/bdd.php');
	include('asset/function.php');
	//head me
	$title="deejay live tv";
	$keywords="deejay,tv-stream,live,webtv,mrdjcraft,twitch";

	//session menu li
	if( isset($_SESSION['id']) )
	{
		$userinfo='			<li><a>'.$_SESSION['name']."</a></li>\n";
		//$userinfo.='		<li><a href="https://api.twitch.tv/kraken/oauth2/revoke?client_id='.$api_id.'&token='.$token.'">logout</a></li>'."\n";
	}
	else{$userinfo='			<li><a href="https://api.twitch.tv/kraken/oauth2/authorize?client_id='.$api_id.'&redirect_uri='.$api_root.'&response_type=token&scope=user_read+chat_login+channel_subscriptions">twitch login</a></li>'."\n";}
	//$pres 1&2
	$pres="";
	$pres2="";
	$reponse = $bdd->prepare('SELECT * FROM `catwoman` order by `id` DESC');
	$reponse->execute();
	$i=1;
	while ($donnees = $reponse->fetch())
	{
		if($i%2){$pres.="					<section>\n						<h2>".$donnees['title']."</h2>\n";}
		else	{$pres2.="					<section>\n						<h2>".$donnees['title']."</h2>\n";}
		$l= $bdd->prepare('SELECT * FROM `posterieur` WHERE `cid`=:id order by `id` DESC LIMIT :view');
		$l->bindValue(':id',$donnees['id'],PDO::PARAM_INT);
		$l->bindValue(':view',intval($donnees['view']),PDO::PARAM_INT);
		$l->execute();
		while ($ar = $l->fetch())
		{
			if($i%2){$pres.="						<article><h3>".$ar['title']."</h3><p>".code($ar['txt'])."</p></article>\n";}
			else	{$pres2.="						<article><h3>".$ar['title']."</h3><p>".code($ar['txt'])."</p></article>\n";}
		}
		$l->closeCursor();
		if($i%2){$pres.="					</section>\n";}
		else	{$pres2.="					</section>\n";}
		$i++;
	}
	//$badges & $badges_act
	if(isset($_SESSION['name']))
	{
		$reponse = $bdd->prepare('SELECT `icoid`,`actived`,`url`,`name` FROM `items`,`pos` WHERE `pos`.`icoid`=`items`.`id` and `uname`=:name');
		$reponse->bindValue(':name',$_SESSION['name'],PDO::PARAM_STR);
		$reponse->execute();
		while ($donnees = $reponse->fetch())
		{	if(!isset($badges)){$badges="";}
			$badges.='<a href="/json/options.php?djbadge='.$donnees['icoid'].'"><img src="/asset/img/djbadge/'.$donnees['url'].'" alt="'.$donnees['name'].'" height="30em"></a>'."\n";
			if($donnees['actived']){$badges_act='<li id="badges-selected"><img src="/asset/img/djbadge/'.$donnees['url'].'" alt="'.$donnees['name'].'" width="40%"></li>';}
		}
		$reponse->closeCursor();
	}
	include('view/index.php');	
