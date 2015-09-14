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
if(isset( $_GET['id'] ) && !isset( $_GET['server'])) {

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
    <script type="text/javascript">cambiatitolo("<?php print str_replace("%id%", $aId, $RGWeb->getLang("report-focus-title", "ret")); ?> - ReporterGUI");</script>

    <h2 style="border-bottom: 1px solid #E6E6E6;"><?php print str_replace("%id%", $aId, $RGWeb->getLang("report-focus-title", "ret")); ?>

     </h2>
     <div class="row">
	      <div class="colonna_50">
          <ul style="margin: 0px;" >
            <li style="font-size: 21px;"><b><?php $RGWeb->getLang("report-focus-reported"); ?> <?php echo $PlayerReport ?></b></li>
            <li style="font-size: 21px;"><?php $RGWeb->getLang("report-focus-reason"); ?> <?php echo $reason; ?></li>
            <li><?php $RGWeb->getLang("report-focus-reportedby"); ?> <?php echo $PlayerFrom; ?></li>
            <li><?php $RGWeb->getLang("report-focus-server"); ?> <?php echo $server; ?></li>
            <li><?php $RGWeb->getLang("report-focus-worldreported"); ?> <?php echo $WorldReport; ?></li>
            <li><?php $RGWeb->getLang("report-focus-worldreportedby"); ?> <?php echo $WorldFrom; ?></li>
            <li><?php $RGWeb->getLang("report-focus-timestamp"); ?> <?php echo $time; ?></li>
            <li><?php $RGWeb->getLang("report-focus-status"); ?> <?php echo strip_tags($RGWeb->getUtily->convertStatusString($status)); ?></li>
          </ul>
        </div>
        <div class="colonna_50">
          <div class="report-focus-userstats">
            <div class="report-focus-stats">
              <img src="<?php echo $RGWeb->getUtily->getUrlServiceAvatarMenu($PlayerReport); ?>" />
              <span><?php print str_replace("%name%", $PlayerReport, $RGWeb->getLang("report-focus-statstitle", "ret")); ?></span>
            </div>
            <ul>
              <li><?php $RGWeb->getStats->setAllReportString($PlayerReport); ?></li>
              <li><?php $RGWeb->getStats->setAllReportSVString($server, $PlayerReport); ?></li>
              <li><br /></li>
              <li><a href="view-report.php?search=PlayerReport&amp;keywords=<?php echo $PlayerReport; ?>&amp;server=<?php echo $server; ?>" ><?php $RGWeb->getLang("report-focus-searchallreport") ?></a></li>
            </ul>
          </div>
        </div>
      </div>
    <br />
    <?php if($RGWeb->getGroup->isHelper() == false) { ?>
    <div class="edit-report-contain">
      <form style="float: right;" id="delete-report" method="post">
        <input name="delete-report" class="bottone-delete" type="submit" value="<?php $RGWeb->getLang("report-focus-delete"); ?>" />
      </form>
      <form style="float: right;" id="editstatus-report" method="post">
        <?php if($status == 1) { ?>
          <input name="editstatus-report" class="bottone-editstatus" type="submit" value="<?php $RGWeb->getLang("report-focus-bottomComplete"); ?>" />
        <?php } ?>
      </form>
    </div>
    <script type="text/javascript">
      $('#delete-report').on('submit', function(event)
      {
        event.preventDefault();
        var notifyconferm = new noty({ text: '<?php print str_replace("%id%", $ID, $RGWeb->getLang("report-focus-deleteConfirm", "ret")); ?>', layout: 'topCenter', type: 'error',theme: 'relax',
        	buttons: [
        		{addClass: 'bottone-delete-conferma', text: '<?php $RGWeb->getLang("report-focus-delete"); ?>', onClick: function($noty) { $noty.close();
            $.ajax({
              type: "POST",
              dataType: 'json',
              url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
              data: "delete-report-id=true",
              success: function(){ },
            });
            redirect('index.php', 5);
          }},
          {addClass: 'bottone-delete-conferma-nope', text: '<?php $RGWeb->getLang("report-focus-deleteback"); ?>', onClick: function($noty) { $noty.close(); }}]
        });
      });

      $('#editstatus-report').on('submit', function(event)
      {
        event.preventDefault();
        $.ajax({
          type: "POST",
          dataType: 'html',
          url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
          data: "edit-report-id=status2",
          success: function(event){
            cambioStatoReport(2, "<?php $RGWeb->getLang("status-complete"); ?>");
          },
        });
      });
    </script>
      <?php
      // If post request is isset then call function deleterepo
      if(isset($_POST['delete-report-id'])) {
        $RGWeb->deleteRepo($aId);
      }
      if(isset($_POST['edit-report-id'])) {
        $RGWeb->editReport($aId, $_POST['edit-report-id']);
      }
    }
  } else {
    print "<div class='container messaggio-errore'>{$RGWeb->getLang("error-report-not-found", "ret")}</div>";
  }
} else {

  // View all report for server
  // Check server is exists
    //
    if(isset($_GET['server'])) {
      if($RGWeb->isServerExists( $_GET['server'] )):
        $server = $RGWeb->escape_html($_GET['server']);
        print "<h2 style=\"border-bottom: 1px solid #E6E6E6;\">Server {$server} - list of reports <a class=\"title-list-reports\" href=\"edit-server.php?name={$server}\">Edit server <i class=\"fa fa-pencil\"></i></a></h2>";
      else:
        print "<br /><div class=\"messaggio-errore\">{$RGWeb->getLang("error-server-not-found", "ret")}</div>";
      endif;
    } else {
      print "<h2 style=\"border-bottom: 1px solid #E6E6E6;\">List of recent reports</h2>";
    }
    # Server
    $server = (!isset($_GET[ 'server' ])) ? null : $RGWeb->escape_html($_GET[ 'server' ]);
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
    $option = array('page' => $page, 'sort' => $sort, 'order' => $order, 'maxint' => $maxint);

    if($search != null):
      $option['search'] = $search[0];
      $option['keywords'] = $search[1];
    endif;
    $RGWeb->outputReportServer($server, $option);
  // else:
  //   print "<br /><div class=\"messaggio-errore\">{$RGWeb->getLang("error-server-not-found", "ret")}</div>";
  // endif;
// else:
// print "<div class='container messaggio-errore'>{$RGWeb->getLang("error-report-not-found", "ret")}</div>";
// endif;
}
$RGWeb->getFooter();
?>
