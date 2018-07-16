<?php
	session_start();
	session_destroy();
	header('Location: /');
	setcookie("token","",time()-1);
