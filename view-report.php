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

    $type = "complete";
    if($PlayerReport == null) {
      $type = "general";
    }
    ?>
      <script type="text/javascript">cambiatitolo("<?php print str_replace("%id%", $aId, $RGWeb->getLang("report-focus-title", "ret")); ?> - ReporterGUI");</script>
      <?php if($type == "complete") { ?>
         <h2 style="border-bottom: 1px solid #E6E6E6; color: #333; font-weight: 300; font-size: 29px"><?php print str_replace("%id%", $aId, $RGWeb->getLang("report-focus-title", "ret")); ?></h2>
      <?php } else { ?>
         <h2 style="border-bottom: 1px solid #E6E6E6; color: #333; font-weight: 300; font-size: 29px"><?php print str_replace("%id%", $aId, $RGWeb->getLang("report-focus-general-title", "ret")); ?></h2>
      <?php } ?>
      <div class="row">
         <div class="colonna_50">
            <ul style="margin: 0px;" >
            <?php if($type == "complete") { ?>
               <li style="font-size: 21px;"><b><?php $RGWeb->getLang("report-focus-reported"); ?> <?php echo $PlayerReport ?></b></li>
               <li style="font-size: 21px;"><?php $RGWeb->getLang("report-focus-reason"); ?> <?php echo $reason; ?></li>
               <li><?php $RGWeb->getLang("report-focus-reportedby"); ?> <?php echo $PlayerFrom; ?></li>
               <li><?php $RGWeb->getLang("report-focus-server"); ?> <?php echo $server; ?></li>
               <li><?php $RGWeb->getLang("report-focus-worldreported"); ?> <?php echo $WorldReport; ?></li>
               <li><?php $RGWeb->getLang("report-focus-worldreportedby"); ?> <?php echo $WorldFrom; ?></li>
               <li><?php $RGWeb->getLang("report-focus-timestamp"); ?> <?php echo $time; ?> - (<?php print $RGWeb->getTimeManager->timeago($time) ?>) </li>
               <li><?php $RGWeb->getLang("report-focus-status"); ?> <?php echo strip_tags($RGWeb->getStatusManager->convertStatusString($status)); ?></li>
            <?php } else { ?>
               <li style="font-size: 21px;"><b><?php $RGWeb->getLang("report-focus-reportedby"); ?> <?php echo $PlayerFrom ?></b></li>
               <li style="font-size: 21px;"><?php $RGWeb->getLang("report-focus-reason"); ?> <?php echo $reason; ?></li>
               <li><?php $RGWeb->getLang("report-focus-server"); ?> <?php echo $server; ?></li>
               <li><?php $RGWeb->getLang("report-focus-worldreportedby"); ?> <?php echo $WorldFrom; ?></li>
               <li><?php $RGWeb->getLang("report-focus-timestamp"); ?> <?php echo $time; ?> - (<?php print $RGWeb->getTimeManager->timeago($time) ?>) </li>
               <li><?php $RGWeb->getLang("report-focus-status"); ?> <?php echo strip_tags($RGWeb->getStatusManager->convertStatusString($status)); ?></li>
            <?php } ?>
            </ul>
         </div>
         <div class="colonna_50">
            <div class="report-focus-userstats">
               <?php if($type == "complete") { ?>
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
               <?php } else { ?>
               <div class="report-focus-stats">
                  <img src="<?php echo $RGWeb->getUtily->getUrlServiceAvatarMenu($PlayerFrom); ?>" />
                  <span><?php print str_replace("%name%", $PlayerFrom, $RGWeb->getLang("report-focus-statstitle", "ret")); ?></span>
               </div>
               <ul>
                  <li><?php $RGWeb->getStats->setAllReportString($PlayerFrom); ?></li>
                  <li><?php $RGWeb->getStats->setAllReportSVString($server, $PlayerFrom); ?></li>
                  <li><br /></li>
                  <li><a href="view-report.php?search=PlayerReport&amp;keywords=<?php echo $PlayerFrom; ?>&amp;server=<?php echo $server; ?>" ><?php $RGWeb->getLang("report-focus-searchallreport") ?></a></li>
               </ul>
               <?php } ?>
            </div>
         </div>
      </div>
      <div class="clear"></div>
         <div class="row report-focus-common" >
            <div class="colonna_50">
               <br /> <br />
               <h3><?php $RGWeb->getLang("report-focus-editstatus") ?></h3>
               <?php
               $RGWeb->getStatusManager->getSelectStatus($aId, $status);
                ?>
               <?php
               // Gestione delle note
               $notes = $RGWeb->getNotes;
               $notes->contrl();
               $notes->setID($aId);
               $notes->render();
               ?>
            </div>
            <div class="colonna_50">
         </div>
      </div>
    <br />
    <?php if($RGWeb->getGroup->isHelper() == false) { ?>
    <div class="edit-report-contain">
      <form style="float: right;" id="delete-report" method="post">
        <input name="delete-report" class="bottone-delete" type="submit" value="<?php $RGWeb->getLang("report-focus-delete"); ?>" />
      </form>
      <form style="float: right;" id="approve-report" method="post">
        <?php if( $status != $RGWeb->getStatusManager->STATUS_APPROVED ) { ?>
          <input name="approve-report" class="bottone-editstatus" type="submit" value="<?php $RGWeb->getLang("report-focus-bottomComplete"); ?>" />
        <?php } ?>
      </form>
    </div>
    <script type="text/javascript">
         $(document).ready(function () {
            $('#delete-report').on('submit', function(event) {
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
            $('#approve-report').on('submit', function(event) {
               event.preventDefault();
               $.ajax({
                  type: "POST",
                  dataType: 'html',
                  url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
                  data: "edit-report-id=approve",
                  success: function(event){
                     location.reload();
                  },
               });
            });
            $('#report-focus-editstatus').on('submit', function(event) {
               event.preventDefault();
                  $.ajax({
                  type: "POST",
                  dataType: 'html',
                  url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
                  data: $( this ).serialize(),
                  success: function(event){
                     location.reload();
                  },
               });
            });
            // Gestisco il form per la modifica finale della nota
            $(document).on('submit', '#input-edit-note form', function (event) {
               event.preventDefault();

               var $this = $(this);
               var text = $this.find("input").val();
               if($(this).attr("id_nota") != null) {
                  var id = $(this).attr("id_nota");
                  $.ajax({
                     type: "POST",
                     dataType: 'html',
                     url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
                     data: "notes-edit="+ id +"&notes-text="+ text,
                     success: function(event) {
                        $(".item_note_"+ id + " .text-common").text(text);
                        $(".item_note_"+ id + " .text-common").show();
                        $(".item_note_"+ id + " .text .input-edit-note").hide();
                     },
                  });
               }
               // Sto aggiungendo la nota
               if($(this).attr("id_nota_moment") != null) {
                  $.ajax({
                     type: "POST",
                     dataType: 'html',
                     url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
                     data: "notes-add=true&notes-text="+ text +"",
                     success: function(event) {
                        location.reload();
                     },
                  });
               }
            });
         });
         // Gestione dell'eliminazione della nota
         function note_delete(id) {
            $.ajax({
               type: "POST",
               dataType: 'html',
               url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
               data: "notes-delete="+ id,
               success: function(event){
                  $(".item_note_"+ id).remove();
               },
            });
         }
         // Preparo il form per la modifica
         function note_edit(id) {
            $(".item_note_"+ id + " .text-common").hide();
            $(".item_note_"+ id + " .text .input-edit-note").show();
            $(".item_note_"+ id + " .text .input-edit-note input").val($(".item_note_"+ id + " .text-common").html());
         }
         // Quando viene digitato il tasto enter dalla tastiera,
         // Viene eseguito il submit del form
         $('.input-edit-note').keydown(function(event) {
            if (event.keyCode == 13) {
               var $this = $(this);
               $this.find("form").submit();
               return false;
            }
         });

         // Aggiungo una nota
         function addnote() {
            // Check if title reports not found is present
            if( $( "#note_notfound" ).length ) {
               $( "#note_notfound" ).remove();
            }
            // Prendo un id random unico
            var unique = unique_id();

            var html = "<div class=\"item item_note_"+ unique +"\" ><div class=\"row\"><div class=\"avatar\">";
            html += "<img src=\"<?php print $RGWeb->getUtily->getUrlServiceAvatarMenu($RGWeb->getUsername()); ?>\"  />";
            html += "</div><div class=\"text\">";
            html += "<div id=\"input-edit-note\" class=\"input-edit-note\"><form id_nota_moment='"+ unique +"'><input type=\"text\" value=\"\" /></form></div>";
            html += "<span class=\"text-common\" ></span>";
            html += "<div class=\"sub\">";
            html += "<span onclick=\"$('.item_note_" + unique + "').remove();\" ><i class=\"fa fa-trash-o\"></i> Delete</span>";
            html += "</div></div></div>";

            $("#notes-viewreprot").append( html );
            $(".item_note_"+ unique + " .text .input-edit-note").show();
         }
    </script>
      <?php
      // If post request is isset then call function deleterepo
      if(isset($_POST['delete-report-id'])) {
         $RGWeb->deleteRepo($aId);
      }
      if(isset($_POST['edit-report-id'])) {
         $RGWeb->getStatusManager->editReport($aId, $_POST['edit-report-id']);
      }
      if(isset($_POST['notes-delete'])) {
         $notes->delete($_POST['notes-delete']);
      }
      if(isset($_POST['notes-edit']) && isset($_POST['notes-text'])) {
         $notes->edit($_POST['notes-edit'], $_POST['notes-text']);
      }
      if(isset($_POST['notes-add']) && isset($_POST['notes-text'])) {
         $notes->add($aId, $_POST['notes-text']);
      }
      if(isset($_POST['changestatus-report'])) {
         $RGWeb->getStatusManager->editReport($aId, $_POST['changestatus-report']);
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
}
$RGWeb->getFooter();
?>
