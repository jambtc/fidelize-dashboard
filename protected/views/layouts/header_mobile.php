<header class="header-mobile d-block d-lg-none">
	<div class="header-mobile__bar">
		<div class="container-fluid">
			<div class="header-mobile-inner">
				<a class='logo' href="<?php echo Yii::app()->createUrl('site/index'); ?>">
					<?php Logo::header(); ?>
				</a>
				<button class="hamburger hamburger--slider" type="button">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>
			</div>
		</div>
	</div>
	<nav class="navbar-mobile">
		<div class="container-fluid">
			<ul class="navbar-mobile__list list-unstyled">
			 <?php
			if (Yii::app()->user->isGuest)
			{
			?>
						<li>
							<a class="js-arrow" href="<?php echo Yii::app()->createUrl('site/login'); ?>">
							<i class="fas fa-sign-in-alt"></i>Login</a>
						</li>
			<?php
			}else{
			?>

				<li class="active">
					<a class="js-arrow" href="<?php echo Yii::app()->createUrl('site/index');?>">Dashboard <i class="fas fa-tachometer-alt"></i></a>
				</li>

				<li class="has-sub" style='display: <?php echo $visible[10]; ?>;'>
					<a class="js-arrow" href="#">
						Pagamenti&nbsp;<i class="fas fa-tasks"></i></a>
					<ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
						<li>
							<a href="<?php echo Yii::app()->createUrl('tokens/index');?>">Tokens <i class="fas fa-star"></i></a>
						</li>
						<li>
							<a href="<?php echo Yii::app()->createUrl('notifications/index');?>">Notifiche <i class="zmdi zmdi-comment-text"></i></a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<a class="js-arrow" href="#">
						Applicazione <i class="fa fa-check-square"></i></a>
					<ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
						<li style='display: <?php echo $visible[20]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('merchants/index');?>">Commercianti <i class="fas fa-industry"></i></a>
						</li>
						<li style='display: <?php echo $visible[10]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('stores/index');?>">Negozi <i class="fa fa-shopping-cart"></i></a>
						</li>
						<li style='display: <?php echo $visible[10]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('pos/index');?>">POS <i class="fa fa-desktop"></i></a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<a class="js-arrow" href="#">
						Amministr. <i class="fa fa-archive"></i></a>
					<ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
						<li style='display: <?php echo $visible[20]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('users/index');?>">Soci <i class="fas fa-users"></i></a>
						</li>
						<li style='display: <?php echo $visible[20]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('users/approve');?>">Iscrizioni <i class="fa fa-eye"></i></a>
						</li>
						<li style='display: <?php echo $visible[20]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('mailing/index');?>">Mail list<i class="zmdi zmdi-email"></i></a>
						</li>
						<?php if (Yii::app()->user->objUser['id_user'] == 1) { ?>
							<li style='display: <?php echo $visible[20]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('log/index');?>">Log<i class="fa fa-list-alt"></i></a>
							</li>
						<?php } ?>
						<li style='display: <?php echo $visible[10]; ?>;'>
							<a href="<?php echo Yii::app()->createUrl('apiConnections/index');?>"><i class="fas fa-chain"></i>API Keys</a>
						</li>
					</ul>
				</li>
				<li class="has-sub" style='display: <?php echo $visible[20]; ?>;'>
					<a class="js-arrow" href="#">
						Tabelle <i class="fas fa-table"></i></a>
					<ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
						<li>
							<a href="<?php echo Yii::app()->createUrl('nodes/index');?>">Nodi POA <i class="fas fa-code-branch"></i></a>
						</li>
					</ul>
				</li>

				<?php } ?>
			</ul>
		</div>
	</nav>
</header>
