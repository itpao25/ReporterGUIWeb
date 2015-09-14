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

Class _RGUpdate
{
  public $linkGithub = "https://github.com/itpao25/ReporterGUIWeb";
  public $spigot = "https://www.spigotmc.org/resources/reporterguiweb.9821/";

  public function checkUpdate() {
    if($this->getLastVersion() == $this->getCurrentVersion()) {
      return true;
    } else {
      return false;
    }
  }
  public function getLastVersion() {
    $commits = strip_tags(file_get_contents("https://raw.githubusercontent.com/itpao25/ReporterGUIWeb/master/inc/version.text.php"));
    return trim($commits);
  }
  public function getCurrentVersion() {
    $path = realpath(dirname(__FILE__));
    $commits = strip_tags(file_get_contents($path. "/version.text.php"));
    return trim($commits);
  }

}
?>
