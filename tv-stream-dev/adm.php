<?php
	session_start();
	if(!isset($_SESSION['adm']) or $_SESSION['adm']==false){header('Location: /');}//nop
	else // is adm
	{
		include('asset/bdd.php');
		$title="Admin Panel";
		
		//actions
		if(isset($_POST))
		{
			//var_dump($_POST);
			if(isset($_POST["give_pts"])&&isset($_POST['pts']))
			{
				$reponse = $bdd->prepare('UPDATE `user` SET `pts` = `pts` + :pts WHERE `pseudale` = :name');
				$reponse->bindValue(':name',htmlentities($_POST["give_pts"]),PDO::PARAM_STR);
				$reponse->bindValue(':pts',htmlentities($_POST["pts"]),PDO::PARAM_STR);
				$reponse->execute();
				$reponse->closeCursor();
			}
			if(isset($_POST["ctitle"]))
			{
				$reponse = $bdd->prepare('INSERT INTO `catwoman`(`title`) VALUES (:title)');
				$reponse->bindValue(':title',htmlentities($_POST["ctitle"]),PDO::PARAM_STR);
				$reponse->execute();
				$reponse->closeCursor();
			}
			elseif(isset($_POST["cat"])&&isset($_POST["title"])&&isset($_POST["txt"]))
			{
				
				$reponse = $bdd->prepare('INSERT INTO `posterieur`(`cid`, `title`, `txt`) VALUES (:id,:title,:txt)');
				$reponse->bindValue(':id',$_POST["cat"],PDO::PARAM_INT);
				$reponse->bindValue(':title',htmlentities($_POST["title"]),PDO::PARAM_STR);
				$reponse->bindValue(':txt',htmlentities($_POST["txt"]),PDO::PARAM_STR);
				$reponse->execute();
				$reponse->closeCursor();
			}
		}
		//affichage
		$userinfo='			<li><a>'.$_SESSION['name']."</a></li>\n";
		//pres 1&2
		include('asset/function.php');
		$pres="";
		$pres2="";
		$cat="";
		$i=1;
		$reponse = $bdd->prepare('SELECT * FROM `catwoman` order by `id` DESC');
		$reponse->execute();
		while ($donnees = $reponse->fetch())
		{
			$cat.='<option value="'.$donnees['id'].'">'.$donnees['title']."</option>\n";
			if($i%2){$pres.="			<section>\n				<h2>".$donnees['title']."</h2>\n";}
			else{$pres2.=	"			<section>\n				<h2>".$donnees['title']."</h2>\n";}
			$l= $bdd->prepare('SELECT * FROM `posterieur` WHERE `cid`=:id order by `id` DESC');
			$l->bindValue(':id',$donnees['id'],PDO::PARAM_INT);
			$l->execute();
			while ($ar = $l->fetch())
			{
				if($i%2){$pres.="				<article><h3>".$ar['title']."</h3><p>".code($ar['txt'])."</p></article>\n";}
				else{$pres2.=	"				<article><h3>".$ar['title']."</h3><p>".code($ar['txt'])."</p></article>\n";}
			}
			$l->closeCursor();
			if($i%2){$pres.="			</section>\n";}
			else{$pres2.=	"			</section>\n";}
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
		//userlist not work why ? select primary key => blank
		$userlist='<option value="">non fonctionel</option>';
		$reponse->closeCursor('SELECT `pseudale` FROM `user`');
		//$reponse->closeCursor('SELECT * FROM `user` WHERE 1 ');
		$reponse->execute();
		while ($donnees = $reponse->fetch()){$userlist.='<option value="'.$donnees['pseudale'].'">'.$donnees['pseudale']."</option>\n";}
		$reponse->closeCursor();
		include('view/adm.php');
	}		
