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

/**
* Classe per le notifiche in tempo reale
* @since 1.5
*/
Class _RGNotify
{
  /* Get time for refresh notify */
  public function getRefresh() {
    global $RGWeb;
    $conf = $RGWeb->getConfig("notify-request");
    print $conf;
  }
  /* Check notify for user is enable */
  public function isNotifyEnable() {
  	global $RGWeb;
  	$id = $RGWeb->getIDLogged();
  	$query = $RGWeb->runQueryMysql("SELECT ID, ifNotify FROM  `webinterface_login`WHERE ID={$id}");
  	$row = mysqli_fetch_assoc($query);
  	return $row['ifNotify'];
  }
  /* Upload status for sound-notify  */
  public function setNotifySoundStatus($boolean) {
    global $RGWeb;
    $id = $RGWeb->getIDLogged();
    $booelan = $boolean =="1" || $boolean =="0" ? $boolean : "1";
    $query = $RGWeb->runQueryMysql("UPDATE `webinterface_login` SET `ifNotify` = '{$boolean}' WHERE `webinterface_login`.`ID` ={$id}");
  }
}
?>
