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

require_once("inc/heart.inc.php");
$RGWeb->getHeader("Users");

if($RGWeb->getGroup->isAdmin() == false) {
  die($RGWeb->getUtily->messageNoPermission());
}

/**
*  Edit user:
*  - Password
*  - Username
*  - Group
* @param id ? user
* @param action
*/
print "<div class=\"container\">";
if(isset($_GET['id'])):

  if($RGWeb->getGroup->isUserIDExits($_GET['id']) == false) die($RGWeb->getUtily->userNotExists());
  $user = $RGWeb->getGroup->getDetailsUser($_GET['id']);
  print "<h2 class=\"h2-head\"><i class=\"fa fa-user\"></i> User {$user[1]}</h2>";
  print "<a href=\"manager-user.php?id={$user[0]}&action=delete&key={$RGWeb->getKeyID()}\"><button class=\"button-primario\"><i class=\"fa fa-times-circle\"></i> Delete user</button></a>";

  if(isset($_GET['action'])):

    $a = $_GET['action'];
    switch ($a) {
      case 'delete':

        // Delete user
        // Controllo prima se la chiave key GET è valida / impostata

        // Check user is not founder (ID 1)
        if($user[0] == 1) {
          print "<br /><div class=\"messaggio-errore\">You can not delete this user!</div>";
          print "<meta http-equiv=\"refresh\" content=\"1; URL=manager-user.php?id={$user[0]}\">";
          return;
        }
        if(isset($_GET['key'])):
          if($RGWeb->checkKeyID($_SESSION['rg_username'], $_GET['key']))
          {
            $RGWeb->deleteUser($user[0]);
            echo "<br /><div class='container messaggio-success'>User {$user[1]} (ID: {$user[0]}) was deleted successfully!</div>";
            echo "<meta http-equiv=\"refresh\" content=\"1; URL=users.php\">";
          }
        endif;
        break;

      default:
        # code...
        break;
    }
  endif;
else:
  print $RGWeb->getUtily->userNotExists();
endif;
if(isset($_POST)) {

}
print "</div>";

$RGWeb->getFooter();
 ?>
