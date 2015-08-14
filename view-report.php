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

// Report single
if(isset( $_GET['id'] ) && !isset( $_GET['server'])):

  // Check report exists
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
elseif(isset( $_GET['server'] )):

  // View all report for server
  // Check server is exists
  if($RGWeb->isServerExists( $_GET['server'] )):
    $server = $RGWeb->escape_html( $_GET['server'] );
    print "<h2 style=\"border-bottom: 1px solid #E6E6E6;\">Server {$server} - list of reports <a class=\"title-list-reports\" href=\"edit-server.php?name={$server}\">Edit server <i class=\"fa fa-pencil\"></i></a></h2>";
    # Pagination
    $page = (!isset($_GET[ 'page' ])) ? 1 : $_GET[ 'page' ];
    # Sort
    $sort = (!isset($_GET[ 'sort' ])) ? "ID" : $_GET[ 'sort' ];
    # Order by
    $order = (!isset($_GET[ 'order' ])) ? "DESC" : $_GET[ 'order' ];
    # Number display reports for page
    $maxint = (!isset($_GET[ 'maxint' ])) ? 20 : $_GET[ 'maxint' ];

    # Search query
    $search = (!isset($_GET[ 'search' ]) || !isset($_GET[ 'keywords' ])) ? null : array($_GET[ 'search' ], $_GET[ 'keywords' ]);

    # Send options
    $option = $arrayName = array('page' => $page, 'sort' => $sort, 'order' => $order, 'maxint' => $maxint);

    if($search != null):
      $option['search'] = $search[0];
      $option['keywords'] = $search[1];
    endif;
      $RGWeb->outputReportServer($server, $option);
    else:
      print "<br /><div class=\"messaggio-errore\">This server does not exist!</div>";
    endif;
else:
print "<div class='container messaggio-errore'>Report not found!</div>";
endif;


$RGWeb->getFooter();
?>
