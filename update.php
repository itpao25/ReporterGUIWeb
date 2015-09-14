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

/* Check user is admin or moderator */
if($RGWeb->getGroup->getGroupID() == 1) {
  die($RGWeb->getUtily->messageNoPermission());
}

print "<h2>Update</h2>";
if($RGWeb->getUpdate->checkUpdate()) {

  $last = $RGWeb->getUpdate->getCurrentVersion();
  print "<div class=\"messaggio-success\">You have the latest version: ". $last ."</div>";

} else {

  $last = $RGWeb->getUpdate->getLastVersion();
  $current = $RGWeb->getUpdate->getCurrentVersion();

  print "<div class=\"messaggio-errore\">You do not have the latest version!
Upgrade to: ". $last;
  print "<br /><b>You have the version: ". $current ."</b><br />";
  print "<br /><b>Download:</b> <br />";
  print "<b>Github: </b> <a href=\"{$RGWeb->getUpdate->linkGithub}\">{$RGWeb->getUpdate->linkGithub}</a><br />";
  print "<b>SpigotMC: </b> <a href=\"{$RGWeb->getUpdate->spigot}\">{$RGWeb->getUpdate->spigot}</a><br />";
  print "</div>";
}
?>
<h3>Information</h3>
This software is free, to work but needs a plugin that can not be redistributed.<br />
When you purchased the plugin you have automatically accepted the terms of license:
<ul>
  <li>You can not redistribute this plugin</li>
  <li>Don't just say "it doesn't work", describe errors.</li>
  <li>Only use in your server or network</li>
  <li>You are not permitted to decompile or modify the plugin in any form</li>
</ul>
I also remember that the source code for the web interface is also available on GitHub, where you can report bugs and advise updates!<br />
<i>This software was created by <a target="_blank" href="https://twitter.com/itpao25">itpao25</a></i><br /><br />
<?php
$RGWeb->getFooter();
?>
