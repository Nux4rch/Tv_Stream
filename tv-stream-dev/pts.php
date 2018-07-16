<?php
	session_start();
	header('Content-Type: application/json');
	if(!isset($_SESSION['id'])){echo '{"error":"not log"}';}
	else// is log 
	{
		if(true)//chech ii in live
		{
			//if( ($_SESSION['time'] > time() ) or !isset($_SESSION['time']))//chech if work
			if(true)
			{
				$_SESSION['time']=time();
				include('asset/bdd.php');
				$reponse = $bdd->prepare('UPDATE `user` SET `pts`=`pts`+1 WHERE `pseudale`=:name');
				$reponse->bindValue(':name',$_SESSION['name'],PDO::PARAM_INT);
				$reponse->execute();
				$reponse->closeCursor();
				$_SESSION['pts'].+1;
				echo '{"error":null}';
			}
			else{ echo '{"error":"time"}'; }
		}
		else{ echo '{"error":"live off"}'; }
	}// (time() - (1 * 60))) 
