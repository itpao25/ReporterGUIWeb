<?php
if (!defined('RG_ROOT')) die();

if($this->isLogged() == false) {
	print "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
	die("You must be logged in to view this page!");
}
?>
<!DOCTYPE html>
<html>
	<head>

		<!--

		ReporterGUI Web Interface  Copyright (C) 2015  itpao25

		This program is free software; you can redistribute it and/or
		modify it under the terms of the GNU General Public License as
		published by the Free Software Foundation; either version 2 of
		the License, or (at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		-->
		<meta charset="utf-8">
		<title><?php echo $Pos ?> - <?php echo $this->getConfig("pageName") ?></title>
		<link href="assets/css/style.css" rel="stylesheet">
		<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
		<script src="assets/js/notify/jquery.noty.packaged.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
		<script src="assets/js/default.js"></script>
		<script>
		</script>
	</head>
	<body>
		<div class="header">
			<div class="container">
				<div class="logo">
					<a href="index.php">
						<img src="<?php echo $this->getUtily->getLogo(); ?>" />
					</a>
				</div>
			</div>
		</div>
		<div class="navbar">
			<div class="container">
				<nav>
					<a> <img class="avatar-menu" src="<?php echo $this->getUtily->getUrlServiceAvatarMenu($this->getUsername()); ?>"> <?php echo $this->getUsername(); ?> </a>
					<a id="menu-Dashboard" href="index.php"><?php $this->getLang("menu-dashboard") ?></a>
					<a id="menu-Servers" href="server-list.php"><?php $this->getLang("menu-serverlist") ?></a>
					<a id="menu-Settings" href="manager-user.php?settings"><?php $this->getLang("menu-settings") ?></a>
					<!-- Admin menu -->
					<?php echo $this->getGroup->getMenuGroup(); ?>
					<a class="menu-right" href="logout.php" ><i class="fa fa-sign-out"></i> <?php $this->getLang("menu-loguting") ?></a>
				</nav>
			</div>
		</div>

		<!-- Gestione della pagina attiva -->
		<?php //if($menu == false): print "test"; ?>
		<?php if($menu == null && $menu != true): ?>
		<script type="text/javascript">
			getMenuItem("<?php echo $Pos; ?>");
		</script>
		<?php endif; ?>
		<!-- Container  -->
		<div class="container-principale container">
			<br />
