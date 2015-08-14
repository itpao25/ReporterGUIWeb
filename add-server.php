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

// Funzione generale per l'aggiunta di un nuovo server Bungeecord

require_once("inc/heart.inc.php");

// Risolvo il problema di sicurezza accessibile dall'esterno facendo la richiesta POST
// In quanto la funzione isLogged() viene chiamata tramite la header, quindi dopo il getHeader()
// Fix in 1.2

if($RGWeb->isLogged() == false) {
  die();
}

/**
* Check user logged is admin
*/
if($RGWeb->getGroup->isAdmin() == true) {

  /* Add user through post request */
  if(isset($_POST['name-server-add'])) {
    $send = $RGWeb->addServer($_POST['name-server-add']);
    die();
  }

  $RGWeb->getHeader("Add server", true);
} else {
  $RGWeb->getHeader("Add server", true);
  /* User logged is not admin */
  die($RGWeb->getUtily->messageNoPermission());
}

?>

<h2 style="border-bottom: 1px dashed #808080;"><i class="fa fa-plus"></i> Add server</h2>
<div class="row">
  <div class="colonna_50">
    <div id="messaggio-add" ></div>
    <form id="add-server" action="add-server.php" class="add-server" method="post">
    <input type="text" name="name-server-add" placeholder="Name" />
    <input type="submit" name="submit-server-add" value="Create" />
    </form>
  </div>
  <div class="colonna_50">
    <div class="box_cont">
      <div class="box-informazioni">
      <h2>Information</h2>
        Use the server name set by config.yml: <br />
        <ul>
          <li>multi-sever-enable: <b>true</b></li>
          <li>server-name: "<b>hub</b>"</li>
        </ul>
        Then add the server as the "<b>hub</b>"
      </div>
  </div>
  </div>
</div>

<!-- use ajax for send a request -->
<script type="text/javascript">
  $("#add-server").submit(function() {
    var url = "add-server.php";
      $.ajax({
         type: "POST",
         url: url,
         data: $("#add-server").serialize(),
         success: function(data)
         {
           $("#messaggio-add").show();
           if(data.trim() == "Server successfully added!")
           {
            if($("#messaggio-add").hasClass("messaggio-errore")){
               $("#messaggio-add").removeClass("messaggio-errore");
            }
            $("#messaggio-add").addClass("messaggio-success");
            redirect("server-list.php", 1000);

           } else
          {
           if($("#messaggio-add").hasClass("messaggio-success")){
             $("#messaggio-add").removeClass("messaggio-success");
           }
           $("#messaggio-add").addClass("messaggio-errore");
          }

          $("#messaggio-add").html(data);
         }
       });
       return false;
  });
</script>
<?php
$RGWeb->getFooter();
?>
