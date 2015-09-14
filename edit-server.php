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


// Funzione generale per la modifica di un server registrato nel database
/**
* Questa funzione utilizza le richieste get per capire che tipo di modifica deve
* effettuare il file
*
* La richiesta riguardante al tipo verrà segnata con "m", then:
* @param get['m'] = tipo di modifica da fare
* @param get['name'] = nome del server da modificare
*/

require_once("inc/heart.inc.php");
$RGWeb->getHeader("Edit server", true);

if(isset($_GET['name']) && !isset($_GET['m']))
{
  // Check if the server exists
  if($RGWeb->isServerExists($_GET['name']))
  {
    $server = $RGWeb->escape_html($_GET['name']);
    ?>
    <div class="row">
      <div class="colonna_50">
        <h2 style="border-bottom: 1px dashed #808080" >Edit server <?php echo $server; ?></h2>
        <div class="box-informazioni">
          <h2><i class="fa fa-pie-chart"></i> Information</h2>
          Total report: <?php echo $RGWeb->getTotalReport($server); ?><br />
          Report complete: <?php echo $RGWeb->getCompleteReport($server); ?><br />
          Report waiting: <?php echo $RGWeb->getWaitingReport($server); ?><br />
        </div>
        <br />
        <a href="edit-server.php?name=<?php echo $server ?>&m=delete&key=<?php echo $RGWeb->getKeyID(); ?>"><button class="button-primario">Delete server</button></a>
      </div>
    </div>
    <?php
  } else {

    // Verificato che il server non esiste, stampo il messaggio di errore
    echo "<br /><div class=\"container\"><div class=\"messaggio-errore\">This server does not exist!</div></div>";
  }

} else if(isset($_GET['m']) && isset($_GET['name']))
{

  $server = $RGWeb->escape_html($_GET['name']);
  $azione = $_GET['m'];

  // Check if the server exists
  if($RGWeb->isServerExists($server))
  {

    switch ($azione) {
      case 'edit':


        break;
      case 'delete':

        /* Check user logged is admin */
        if($RGWeb->getGroup->isAdmin() == false) {
          die($RGWeb->getUtily->messageNoPermission());
        }

        if(isset($_GET['key'])):

          if($RGWeb->checkKeyID($_SESSION['rg_username'], $_GET['key']))
          {
            $RGWeb->deleteServer($server);
            echo "<br /><div class='container messaggio-success'>The server {$server} was deleted successfully!</div>";
            echo "<meta http-equiv=\"refresh\" content=\"1; URL=server-list.php\">";
          } else
          {
            echo "<br /><div class=\"container\"><div class=\"messaggio-errore\">The key user is not valid!</div></div>";
          }

        endif;
        break;

      default:

        /* Nessuna azione trovata per la richiesta get name */
        echo "<br /><div class=\"container\"><div class=\"messaggio-errore\">This action is not valid!</div></div>";
        break;
      }
  } else {
    echo "<br /><div class=\"container\"><div class=\"messaggio-errore\">This server does not exist!</div></div>";
  }

}

$RGWeb->getFooter();


?>
