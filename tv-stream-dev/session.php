<?php
	session_start();
	$token=htmlentities($_GET['access_token']);
	include('asset/twitch.php');
	include('asset/bdd.php');
	if(isset($token))//twitch login
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.twitch.tv/kraken?oauth_token=".$token."&client_id=".$api_id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		$js=json_decode($output);
		if($js->{'token'}->{'valid'})
		{	
			setcookie("id",$js->{'token'}->{'client_id'},time()+(1*60*60));
			$reponse = $bdd->prepare('SELECT * FROM `user` WHERE `pseudale` =:name');
			$reponse->bindValue(':name',$js->{'token'}->{'user_name'},PDO::PARAM_STR);
			$reponse->execute();
			if($donnees = $reponse->fetch())
			{
				$_SESSION['adm']   =  $donnees['adm'];
				$_SESSION['pts']   =  $donnees['pts'];
			}
			else
			{
				$ins = $bdd->prepare('INSERT INTO `user`(`pseudale`) VALUES (:uname)');
				$ins->bindValue(':uname',$js->{'token'}->{'user_name'},PDO::PARAM_STR);
				$ins->execute();
				$ins->closeCursor();
				$_SESSION['adm']   =  false;
				$_SESSION['pts']   =  0;
			}
			$_SESSION['id']    = $js->{'token'}->{'client_id'};
			$_SESSION['token'] = $token;
			$_SESSION['name']  = $js->{'token'}->{'user_name'};
			$reponse->closeCursor();
			$https=false;
			$script_read=false;
			setcookie ( "token", $token , time()+(1*24*60*60) , "/index.php" , "dev.tv-stream.fr" , $https, $script_read );//1 j
		}
	}
	header('Location: /');
