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
$RGWeb->getHeader("Update");

print "<div class=\"container\"><br />";
/* Check user is admin */
if($RGWeb->getGroup->isAdmin() == false) {
  die($RGWeb->getUtily->messageNoPermission());
}

if($RGWeb->getUpdate->checkUpdate()) {

  $last = $RGWeb->getUpdate->getCurrentVersion();
  print "You have the latest version: ". $last;
} else {
  $last = $RGWeb->getUpdate->getLastVersion();
  print "You do not have the latest version!
Upgrade to: ". $last;
}

print "</div>";
$RGWeb->getFooter();
?>
