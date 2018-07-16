<header>
			<nav id="main-h">
				<ul>
					<li><img src="https://static-cdn.jtvnw.net/jtv_user_pictures/0d017488f95ee7c3-profile_image-300x300.jpeg" height="30em"></li>
					<li id="streameur"><h2>DEEJAY</h2></li>
				</ul>
				<a href="/"><h1 id="live-name">tv-stream</h1></a>
				<ul>
		<?php echo $userinfo; ?>
				</ul>
				<button onclick="c()">menu</button>
			</nav>
			<!--nav id="mcdo" class="open"-->
			<nav id="mcdo">
				<div>
					<button onclick="c()">close menu</button>
					<ul class="user">
						<li><a><?php echo ( !empty($_SESSION['name']) )? $_SESSION['name'] :'anonyme'; ?></a></li>
						<br>
						<?php echo ( isset($badges_act) )? $badges_act :""; ?>
						<br>
						<?php echo ( isset($badges) )?'<li id="badges-select">'.$badges."</li>\n":''; ?>
						<li><div id="rank"><?php echo (!empty($_SESSION) and $_SESSION['adm']==true)?'admin':'viewer'; ?></div></li>
						<li><?php echo (isset($_SESSION['pts']))? $_SESSION['pts'].' pts':'Vous n’êtes pas connecté'; ?></li>
					</ul>
				</div>
				<hr>
				<div>
					<ul>
						<li><a href="/">Accueil</a></li>
						<li><a href="/shop.php">Shop</a></li>
						<?php echo (!empty($_SESSION) and $_SESSION['adm']==true)?'<li><a href="/adm.php">Admin Panel</a></li>'."\n":"\n"; ?>
						<?php echo (isset($_SESSION['name']))?'<li><a href="/d.php">Logout</a></li>'."\n":"\n"; ?>
					</ul>
				</div>
			</nav>
		</header>
