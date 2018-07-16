		<head>
			<script type="text/javascript">
				var urlhash = document.location.hash;
				if(new RegExp(/#access_token=[a-z0-9]{30}/).test(urlhash)){console.log("session token detected");window.location.replace("/session.php"+urlhash.replace("#","?"));}
			</script><!-- session -->
			<meta charset="UTF-8">
			<meta name="LANGUAGE" content="FR">
			<meta name="Content-Language" content="fr">
			<title><?php echo (!empty($title))?$title.' || tv-stream':'tv-stream'; ?></title>
			<?php echo (!empty($keywords))?'<meta name="keywords" content="'.$keywords.'">':''; ?>
			<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> not resp wh-->
			<link rel="stylesheet" type="text/css" href="/asset/css/style.css">
			<link rel="stylesheet" type="text/css" href="/asset/css/tags.css">
			<link rel="stylesheet" type="text/css" href="/asset/css/fonts.css">
			<link rel="stylesheet" type="text/css" href="/asset/css/chat.css">
			<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"><!-- fa ico cdn-->
		</head>
