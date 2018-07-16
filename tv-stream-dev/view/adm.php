<!doctype html>
<html lang="fr">
<?php include('asset/head.php'); ?>
	<body>
		<?php include('header.php'); ?>
		<main>
			<div style="display: flex;justify-content: space-around;">
				<section id="create">
					<form method="post">
						<fieldset>
							<legend>creer une categorie</legend>
							title: <input type="text" name="ctitle" >
							<input type="submit" value="Submit">
						</fieldset>
					</form>
					<hr>
					<form method="post">
						<fieldset>
							<legend>creer un article</legend>
							<select name="cat">
								<?php echo $cat ?>
							</select>
							title: <input type="text" name="title" ><br>
							<textarea name="txt" rows="4" cols="50"></textarea><br>
							<input type="submit" value="Submit">
						</fieldset>
					</form>
					<form method="post">
						<fieldset>
							<legend>donner pts</legend>
							<select name="give_pts">
								<?php echo $userlist ?>
							</select>
							pts: <input type="text" name="pts" >
							<input type="submit" value="Submit">
						</fieldset>
					</form>
					<form method="post">
						<fieldset>
							<legend>donner pts (temp)</legend>
							full username (warn maj): <input type="text" name="give_pts" >
							pts: <input type="text" name="pts" >
							<input type="submit" value="Submit">
						</fieldset>
					</form>
				</section>
				<section id="live-chat">
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
				</section>
			</div>
			<section id="pres"><!-- présentation -->
				<div>
				<?php echo $pres; ?>
				</div>
				<div>
				<?php echo $pres2; ?>
				</div>
			</section>
		</main>
		<?php include('footer.php'); ?>
		<script type="text/javascript">var token="<?php echo (!empty($_SESSION['token']))? $_SESSION['token']:''; ?>";var uname="<?php echo (!empty($_SESSION['name']))? $_SESSION['name']:''; ?>";</script>
		<script type="text/javascript" src="//code.jquery.com/jquery-3.2.1.min.js"></script><!-- jquery -->
		<script type="text/javascript" src="/asset/js/main.js"></script><!-- menu badge selector-->
		<script type="text/javascript" src="//cdn.tmijs.org/js/1.2.1/tmi.min.js" integrity="sha384-eE0n7sm1W7DOUI2Xh5I4qSpZTe6hupAO0ovLfqEy0yVJtGRBNfssdmjbJhEYm6Bw" crossorigin="anonymous"></script><!--for chat.js-->
		<script type="text/javascript" src='https://twemoji.maxcdn.com/2/twemoji.min.js?2.3.0'></script><!--for chat.js-->
		<script type="text/javascript" src="/asset/js/chat.js"></script>
	</body>
</html>
