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
$RGWeb->getHeader("View report", true);
?>
<div class="container">
<br />
<?php
if(isset( $_GET['id'] ))

  if($RGWeb->isReportExist($_GET['id'])) {

    $ID = strip_tags($_GET['id']);
    $info = $RGWeb->getReportInfo($ID);

    $aId = strip_tags($info[0]);
		$PlayerReport = strip_tags($info[1]);
		$PlayerFrom = strip_tags($info[2]);
		$reason = strip_tags($info[3]);
		$WorldReport = strip_tags($info[4]);
		$WorldFrom = strip_tags($info[5]);
		$time = strip_tags($info[6]);
		$server = strip_tags($info[7]);
		$status = strip_tags($info[8]);

    ?>
    <script type="text/javascript">cambiatitolo("Report #<?= $ID; ?> - ReporterGUI");</script>
    <h2 style="border-bottom: 1px solid #E6E6E6;">Report #<?= $aId; ?> </h2>
    <ul>
      <li>Player reported: <?= $PlayerReport ?></li>
      <li>Reason: <?= $reason; ?></li>
      <li>Reported by: <?= $PlayerFrom; ?></li>
      <li>World reported: <?= $WorldReport; ?></il>
      <li>World player of that reported: <?= $WorldFrom; ?></li>
      <li>Timestamp: <?= $time; ?></li>
      <li>Status: <?= $status; ?></li>
    </ul>
    <br />

    <?php
    //print_r($info);
  } else {
    print "<div class='container messaggio-errore'>Report not found!</div>";
  }

else
print "<div class='container messaggio-errore'>Report not found!</div>";
?>
</div>
<?php
$RGWeb->getFooter();
?>
