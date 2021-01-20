<aside class="menu-sidebar d-none d-lg-block">
	<div  class="logo">
		<a href="<?php echo Yii::app()->createUrl('site/index'); ?>">
			<?php Logo::header(); ?>
		</a>
	</div>
	<div id="page-vesuvio"></div>
	<div class="menu-sidebar__content js-scrollbar1">
		<nav class="navbar-sidebar">
			<?php
			if (Yii::app()->user->isGuest)
			{
			?>
				<ul class="list-unstyled navbar__list">
					<li class="active">
						<a class="js-arrow" href="<?php echo Yii::app()->createUrl('site/login'); ?>">
							<i class="fas fa-sign-in-alt"></i>Login</a>
					</li>
				</ul>
			<?php
			}else{
				?>
				<ul class="list-unstyled navbar__list">
					<li class="active">
						<a class="js-arrow" href="<?php echo Yii::app()->createUrl('site/index');?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
					</li>

					<li class="has-sub" style='display: <?php echo $visible[10]; ?>;'>
						<a class="js-arrow" href="#">
							<i class="fas fa-tasks"></i>Gestione pagamenti</a>
						<ul class="list-unstyled navbar__sub-list js-sub-list">
							<li>
								<a href="<?php echo Yii::app()->createUrl('tokens/index');?>"><i class="fas fa-star"></i>Tokens</a>
							</li>
							<li>
								<a href="<?php echo Yii::app()->createUrl('notifications/index');?>"><i class="fas fa-bell"></i>Notifiche</a>
							</li>

						</ul>
					</li>
					<li class="has-sub">
						<a class="js-arrow" href="#">
							<i class="fa fa-check-square"></i>Gestione Applicazione</a>
						<ul class="list-unstyled navbar__sub-list js-sub-list">
							<!-- <li style='display: <?php echo $visible[20]; ?>;'>
								<a href="<?php //echo Yii::app()->createUrl('associations/index');?>"><i class="fas fa-sitemap"></i>Associazioni</a>
							</li> -->
							<li style='display: <?php echo $visible[20]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('merchants/index');?>"><i class="fas fa-industry"></i>Commercianti</a>
							</li>
							<li style='display: <?php echo $visible[10]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('stores/index');?>"><i class="fas fa-shopping-cart"></i>Negozi</a>
							</li>
							<li style='display: <?php echo $visible[10]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('pos/index');?>"><i class="fa fa-desktop"></i>POS</a>
							</li>
						</ul>
					</li>


					<li class="has-sub">
						<a class="js-arrow" href="#">
							<i class="fa fa-archive"></i>Amministrazione</a>
						<ul class="list-unstyled navbar__sub-list js-sub-list">

							<li style='display: <?php echo $visible[20]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('users/index');?>"><i class="fas fa-users"></i>Soci</a>
							</li>
							<li style='display: <?php echo $visible[20]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('users/approve');?>"><i class="fa fa-eye"></i>Richieste Iscrizione</a>
							</li>
							<li style='display: <?php echo $visible[20]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('mailing/index');?>"><i class="zmdi zmdi-email"></i>Mail list</a>
							</li>
							<?php if (Yii::app()->user->objUser['id_user'] == 1) { ?>
								<li style='display: <?php echo $visible[20]; ?>;'>
									<a href="<?php echo Yii::app()->createUrl('log/index');?>"><i class="fa fa-list-alt"></i>Log</a>
								</li>
								<li style='display: <?php echo $visible[20]; ?>;'>
									<a href="<?php echo Yii::app()->createUrl('rulesenginerequests/index');?>"><i class="fa fa-cogs"></i>RE Requests</a>
								</li>
							<?php } ?>
							<li style='display: <?php echo $visible[10]; ?>;'>
								<a href="<?php echo Yii::app()->createUrl('api/index');?>"><i class="fas fa-chain"></i>API Keys</a>
							</li>
						</ul>
					</li>
					<li class="has-sub" style='display: <?php echo $visible[20]; ?>;'>
						<a class="js-arrow" href="#">
							<i class="fas fa-table"></i>Menù Tabelle</a>
						<ul class="list-unstyled navbar__sub-list js-sub-list">
							<li>
								<a href="<?php echo Yii::app()->createUrl('nodes/index');?>"><i class="fas fa-code-branch"></i>Nodi POA</a>
							</li>
							<li>
								<a href="<?php echo Yii::app()->createUrl('userstype/index');?>"><i class="fas fa-user-md"></i>Tipo utenti</a>
							</li>
						</ul>
					</li>
				</ul>
			<?php } ?>
		</nav>
	</div>
</aside>
