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

Class _RGStats
{
  /**
  * Get int total report for username
  * @since 1.6
  * @param Username
  * @return return int
  */
  public function getIntTotalReportPlayer($username) {
    global $RGWeb;

    if(!$RGWeb->isUserReported($username)) return 0;
    $user = $RGWeb->real_escape_string($username);
    $query = $RGWeb->runQueryMysql("SELECT PlayerReport FROM `reporter` WHERE `PlayerReport`='$user'");
    return $query->num_rows;
  }

  /**
  * Get int total report completed for username
  * @since 1.6
  * @param Username
  * @return return int
  */
  public function getIntTotalReportCOPlayer($username) {
    global $RGWeb;

    if(!$RGWeb->isUserReported($username)) return 0;
    $user = $RGWeb->real_escape_string($username);
    $query = $RGWeb->runQueryMysql("SELECT PlayerReport FROM `reporter` WHERE `PlayerReport`='$user' AND status=2");
    return $query->num_rows;
  }

  /**
  * Get int total report in server
  * @since 1.6
  * @param server Nome del server
  * @param Username
  * @return return int
  */
  public function getReportForServer($server, $username) {
    global $RGWeb;

    if(!$RGWeb->isUserReported($username)) return 0;
    $user = $RGWeb->real_escape_string($username);
    $query = $RGWeb->runQueryMysql("SELECT PlayerReport FROM `reporter` WHERE `PlayerReport`='$user' AND server='$server'");
    return $query->num_rows;
  }

  /**
  * Get int total report completed in server
  * @since 1.6
  * @param server Nome del server
  * @param Username
  * @return return int
  */
  public function getReportCOForServer($server, $username) {
    global $RGWeb;

    if(!$RGWeb->isUserReported($username)) return 0;
    $user = $RGWeb->real_escape_string($username);
    $query = $RGWeb->runQueryMysql("SELECT PlayerReport FROM `reporter` WHERE `PlayerReport`='$user' AND server='$server' AND status=2");
    return $query->num_rows;
  }

  /**
  * Set string in report stats string
  * @since 1.6
  * @return return string
  */
  public function setAllReportString($player) {
    global $RGWeb;

    $string = $RGWeb->getLang("report-focus-totalreport", "ret");
    $string = str_replace("%tot%", $this->getIntTotalReportPlayer($player), $string);
    $string = str_replace("%totco%", $this->getIntTotalReportCOPlayer($player), $string);
    print $string;
    return;
  }

  /**
  * Set string in report stats string for server
  * @since 1.6
  * @return return string
  */
  public function setAllReportSVString($server, $player) {
    global $RGWeb;

    $string = $RGWeb->getLang("report-focus-totalreportserver", "ret");
    $string = str_replace("%tot%", $this->getReportForServer($server, $player), $string);
    $string = str_replace("%server%", $server, $string);
    $string = str_replace("%totco%", $this->getReportCOForServer($server, $player), $string);
    print $string;
    return;
  }



}

?>
