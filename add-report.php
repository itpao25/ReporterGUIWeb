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
if($RGWeb->isLogged() == false) {
   print "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
   die("You must be logged in to view this page!");
}
if($RGWeb->getGroup->isHelper()) {
   // L'utente helper non può aggiungere un report manualmente
   print "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
   die("You must be logged in to view this page!");
}
if(isset($_POST['addreport-verify'])) {
   $reported = isset($_POST['addreport-input-reported']) ? $_POST['addreport-input-reported'] : NULL;
   $reportby = isset($_POST['addreport-input-reportby']) ? $_POST['addreport-input-reportby'] : NULL;
   $reason = isset($_POST['addreport-input-reason']) ? $_POST['addreport-input-reason'] : NULL;
   $nameworld = isset($_POST['addreport-input-worldplayer']) ? $_POST['addreport-input-worldplayer'] : NULL;
   $nameworldfrom = isset($_POST['addreport-input-worldplayerfrom']) ? $_POST['addreport-input-worldplayerfrom'] : NULL;
   $server = isset($_POST['addreport-input-server']) ? $_POST['addreport-input-server'] : NULL;

   $result = array(
      "reported" => $reported,
      "reportby" => $reportby,
      "reason" => $reason,
      "world_reported" => $nameworld,
      "world_reportby" => $nameworldfrom,
      "server" => $server,
   );
   header('Content-Type: application/json');
   $request = $RGWeb->addReport($result);
   print $request;
   die();
}

$RGWeb->getHeader("Add report", true);
?>
<h2><?php echo $RGWeb->getLang("addreport-title"); ?></h2>
<div class="row">
   <div class="colonna_60">
      <form id="addreport-form" class="addreport-form" >
         <label for="addreport-input-reported"><?php echo $RGWeb->getLang("addreport-input-reported"); ?></label>
         <input name="addreport-input-reported" placeholder="Nickname of the player to report" id="addreport-input-reported" type="text" required />
         <label for="addreport-input-by"><?php echo $RGWeb->getLang("addreport-input-reportby"); ?></label>
         <input name="addreport-input-reportby" placeholder="Nickname of the player who sent the report" id="addreport-input-by" type="text" value="<?php echo $RGWeb->getUsername(); ?>" required />
         <label for="addreport-reason"><?php echo $RGWeb->getLang("addreport-input-reason"); ?></label>
         <textarea name="addreport-input-reason" id="addreport-reason" placeholder="Reason for reporting" required /></textarea>

         <label for="addreport-input-server"><?php echo $RGWeb->getLang("addreport-input-server"); ?></label>
         <select id="addreport-input-server" name="addreport-input-server" >
            <?php print $RGWeb->getServerListSelect(); ?>
         </select>

         <label for="addreport-input-worldplayer"><?php echo $RGWeb->getLang("addreport-input-worldplayer"); ?></label>
         <input name="addreport-input-worldplayer" id="addreport-input-worldplayer" type="text" />
         <label for="addreport-input-worldplayerfrom"><?php echo $RGWeb->getLang("addreport-input-worldfrom"); ?></label>
         <input name="addreport-input-worldplayerfrom" id="addreport-input-worldplayerfrom" type="text" />

         <input type="hidden" name="addreport-verify" value="true" />
         <input type="submit" name="addreport-submit" />
      </form>
   </div>
   <div class="colonna_40">
   </div>
</div>
<script type="text/javascript">
   $(document).on('submit', '#addreport-form', function (event) {
      event.preventDefault();
      $.ajax({
        type: "POST",
        dataType: 'json',
        url: "<?php echo $RGWeb->getUtily->selfURL(); ?>",
        data: $( this ).serialize(),
        success: function(event){
          if(event.success != null) {
             redirect("view-report.php?id="+ event.success, 1000);
             return;
          }
          alert("An error occurred");
        },
      });

   });
</script>
<?php
$RGWeb->getFooter();
 ?>
