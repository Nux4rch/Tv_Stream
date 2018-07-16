<!doctype html>
<html lang="fr">
<?php include('asset/head.php'); ?>
	<body>
		<?php include('header.php'); ?>
		<main>
			<section id="shop">
				<h2 class="placeholder" style="text-align: center;">Shop</h2>
				<br>
				<p><?php echo $msg; ?> </p>
				<div class="thumbs">
					<?php echo $items ; ?>
				</div>
			</section>
		</main>
		<?php include('footer.php'); ?>
		<script type="text/javascript">var token="<?php echo (!empty($_SESSION['token']))? $_SESSION['token']:''; ?>"</script>
		<script type="text/javascript" src="//code.jquery.com/jquery-3.2.1.min.js"></script><!-- jquery -->
		<script type="text/javascript" src="/asset/js/main.js"></script><!-- menu badge selector-->
	</body>
</html>