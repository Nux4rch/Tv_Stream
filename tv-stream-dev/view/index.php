<!doctype html>
<html lang="fr">
<?php include('asset/head.php'); ?>
	<body>
		<?php include('header.php'); ?>
		<main>
			<section id="live">
				<!--<div id="live-info">
					<ul>
						<li><img src="https://static-cdn.jtvnw.net/jtv_user_pictures/0d017488f95ee7c3-profile_image-300x300.jpeg" height="30em"></li>
						<li id="streameur"><h2>DEEJAY</h2></li>
					</ul>
					<a href="/"><h1 id="live-name">tv-stream</h1></a>
				</div>-->
				<div id="live-statu">
						<p>
						<span id="live-on">Offline</span>
							<br>
						<span id="live-game"> No Game</span>
						</p>
					<hr>
						<p>
						<span id="live-viewer">Viewers:</span>
							<br>
						<span id="live-viewers"> 0 </span>
						</p>
				</div>
				<br>
				<div id="live-container">
					<!-- <div id="pub"></div>-->
					<div id="live-stream">
						<noscript>Your browser does not support JavaScript!</noscript>
					</div>
					<div id="live-chat">
						<div class="header" style="border-bottom: 1px solid #000000;">
							<p class="roomtitle" style="margin-top: 2px;">Groin-Box</p>
						</div>	
						<div id="live-chat-msg">
							<noscript>Your browser does not support JavaScript!</noscript>
						</div>
						<form id="live-chat-form" style="display: flex;">
							<textarea rows="4" disabled id="live-chat-post"></textarea>
							<br>
							<input type="submit" value="Submit">
						</form>
					</div>
				</div>
				<br><br>
			</section>
			<section id="pres"><!-- présentation -->
				<div id="pres1">
<?php echo $pres; ?>
				</div>
				<div id="pres2">
<?php echo $pres2; ?>
				</div>
			</section>
		</main>
		<?php include('footer.php'); ?>
		
		<script type="text/javascript">var token="<?php echo (!empty($_SESSION['token']))? $_SESSION['token']:''; ?>";var uname="<?php echo (!empty($_SESSION['name']))? $_SESSION['name']:''; ?>";</script><!-- token and name-->
		<script type="text/javascript" src="//code.jquery.com/jquery-3.2.1.min.js"></script><!-- jquery -->
		<script type="text/javascript" src="/asset/js/main.js"></script><!-- menu badge selector-->
		<script type="text/javascript" src="//cdn.tmijs.org/js/1.2.1/tmi.min.js" integrity="sha384-eE0n7sm1W7DOUI2Xh5I4qSpZTe6hupAO0ovLfqEy0yVJtGRBNfssdmjbJhEYm6Bw" crossorigin="anonymous"></script><!--for chat.js-->
		<script type="text/javascript" src='https://twemoji.maxcdn.com/2/twemoji.min.js?2.3.0'></script><!--for chat.js-->
		<script type="text/javascript" src= "http://player.twitch.tv/js/embed/v1.js"></script><!--for twitch.js-->
		<script type="text/javascript" src="/asset/js/twitch.js"></script>
		<script type="text/javascript" src="/asset/js/chat.js"></script>
		<script type="text/javascript">//autoscrool
		window.setInterval(function() {
		var elem = document.getElementById('live-chat-msg');
		elem.scrollTop = elem.scrollHeight;
		}, 500);
		</script>
	</body>
</html>
				
