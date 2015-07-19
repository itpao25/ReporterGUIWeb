<?php

	if (!defined('RG_ROOT')) die();

	if($this->isLogged() == false)
    die("Devi essere loggato per poter vedere questa pagina!");

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
		<title><?= $Pos ?> - <?=$this->getConfig("pageName") ?></title>
		<link href="assets/css/style.css" rel="stylesheet">
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
		<script src="assets/js/default.js"></script>
	</head>
	<body>
		<div class="header">
			<div class="container">
				<div class="logo">
					<img src="assets/img/logo-rgui.png" />
				</div>
			</div>
			<div class="navbar">
				<div class="container">
					<nav>
						<a id="menu-Dashboard" href="index.php">Dashboard</a>
						<a id="menu-Servers" href="server-list.php">Server list</a>
						<a class="menu-right" href="logout.php" ><i class="fa fa-sign-out"></i> Logout</a>
					</nav>
				</div>
			</div>
		</div>

		<!-- Gestione della pagina attiva -->
		<?php //if($menu == false): print "test"; ?>
		<?php if($menu == null && $menu != true): ?>
		<script type="text/javascript">
			getMenuItem("<?= $Pos; ?>");
		</script>
		<?php endif; ?>
