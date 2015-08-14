<?php
/**
*  This file is a class of ReporterGUI
*
*  @author itpao25
*  ReporterGUI Web Interface  Copyright (C) 2015
*
*  This program is free software; you can redistribute it and/or
*  modify it under the terms of the GNU General Public License as
*  published by the Free Software Foundation; either version 2 of
*  the License, or (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  @package ReporterGUI
*/

if (!defined('RG_ROOT')) die();
if (!defined('RG_ROOT_INDEX')) die();

if($this->isLogged() == true)
  die("Sei già loggato!");

?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - ReporterGUI</title>
  <meta charset="utf-8">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
  <link href="assets/css/login.css" rel="stylesheet" />
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
</head>
<body>
  <div class="login-contenitore">
    <img class="pulse animated" alt="" src="assets/img/logo-rgui.png" />
    <?php

    if(isset($_GET['logout']) && $_GET['logout'] == "true")
    {
      $this->getUtily->logoutSuccess();
    }
    if(isset($_POST['gdsmrfgm']) && isset($_POST['gdsmrfgdm']) && isset($_POST['bfghca']))
    {

      /**
      * authLogin è la funzione principale che permette di verificare se le
      * variabili post e get sono corrette
      * authLogin is a function for check a login is corret
      *
      * @param POST['gdsmrfgm'] = username
      * @param POST['gdsmrfgdm'] = password
      */
      if($this->authLogin($_POST['gdsmrfgm'], $_POST['gdsmrfgdm']))
      {
        $this->getUtily->loginSuccess();
      } else {
        $this->getUtily->loginError();
      }

    }
    ?>
    <div class="box-login">
      <p class="benvenuto-text">
        Welcome Back. Login please
      </p>
      <form enctype="application/x-www-form-urlencoded" action="index.php" method="post" >
        <input placeholder="Username" type="text" name="gdsmrfgm" />
        <input placeholder="Password" type="password" name="gdsmrfgdm" />
        <input type="submit" value="Login" name="bfghca" />
      </form>
    </div>
  </div>
</body>
</html>
