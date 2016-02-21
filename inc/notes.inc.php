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
Class _RGNotes {

   /**
   * @var Id of report
   * @since 1.6.2
   */
   private $id_report;

   /**
   * Creo la tabella reporter_notes se non esiste
   * @since 1.6.2
   */
   public function contrl() {
      global $RGWeb;

      // Controllo se la tabella 'reporter_notes'
      // Se non esiste creo la tabella
      $query = $RGWeb->runQueryMysql("SHOW TABLES LIKE 'reporter_notes'");
      if($query->num_rows !=1) {
         $query = "CREATE TABLE IF NOT EXISTS reporter_notes (ID int NOT NULL AUTO_INCREMENT PRIMARY KEY, id_report int(11), insert_by varchar(120), date varchar(30), text varchar(320))";
         $RGWeb->runQueryMysql($query);
      }
   }
   /**
   * Set id of report
   * @since 1.6.2
   */
   public function setID($id_report) {
      global $RGWeb;

      $id = $RGWeb->real_escape_string($id_report);
      $this->id_report = $id;
   }


   /**
   * Render output notes list
   */
   public function render() {
      global $RGWeb;

      $query = "SELECT * FROM reporter_notes WHERE id_report = '$this->id_report'";
      $excute = $RGWeb->runQueryMysql($query);
      $int = 0;
      print "<div id=\"notes-viewreprot\" class=\"notes-viewreprot\"><h3>{$RGWeb->getLang("notes-viewreprot-title", "ret")} <span onclick=\"addnote()\" style=\"float:right\"><i class=\"fa fa-plus\"></i></span></h3>";

      while($row = $excute->fetch_array()) {
         print "<div class=\"item item_note_{$RGWeb->escape_html($row['ID'])}\"><div class=\"row\"><div class=\"avatar\">
         <img src=\"{$RGWeb->getUtily->getUrlServiceAvatarMenu($row['insert_by'])}\"  />
         </div><div class=\"text\">
         <div id=\"input-edit-note\" class=\"input-edit-note\"><form id_nota=\"{$RGWeb->escape_html($row['ID'])}\" ><input type=\"text\" value=\"\" /></form></div>
         <span class=\"text-common\" > {$RGWeb->escape_html($row['text'])} </span>
         <div class=\"sub\">
         <span onclick=\"note_delete({$RGWeb->escape_html($row['ID'])})\" ><i class=\"fa fa-trash-o\"></i> {$RGWeb->getLang("notes-delete", "ret")}</span>
         -
         <span onclick=\"note_edit({$RGWeb->escape_html($row['ID'])})\" ><i class=\"fa fa-edit\"></i> {$RGWeb->getLang("notes-edit", "ret")}</span> </div>
         </div></div></div>";
         $int++;
      }
      if($int == 0) {
         print $RGWeb->getLang("notes-not-found", "ret");
      }
      print "</div>";
   }

   /**
   * Delete a note
   * @since 1.6.2
   * @return boolean val
   */
   public function delete($id) {
      global $RGWeb;

      $id = $RGWeb->real_escape_string($id);
      $query = ("DELETE FROM `reporter_notes` WHERE `reporter_notes`.`ID` = '$id'");
      $excute = $RGWeb->runQueryMysql($query);
      // Log
      $RGWeb->addLogs("Note deleted #{$id}");
   }

   /**
   * Edit the text of note
   * @since 1.6.2
   * @param id Id of note
   * @param text Text of note
   */
   public function edit($id, $text) {
      global $RGWeb;

      $id = $RGWeb->real_escape_string($id);
      $text = $RGWeb->real_escape_string($text);
      $text = $RGWeb->escape_html($text);
      $query = ("UPDATE `reporter_notes` SET `text` = '$text' WHERE `reporter_notes`.`ID` = '$id'");
      $excute = $RGWeb->runQueryMysql($query);
      // Log
      $RGWeb->addLogs("Note edited #{$id}, text \"{$text}\"");
   }

   /**
   * Add the note for report
   * @since 1.6.2
   * @param text Testo della nota che deve essere aggiunto
   */
   public function add($id_report, $text) {
      global $RGWeb;

      $text = $RGWeb->real_escape_string($text);
      $text = $RGWeb->escape_html($text);
      $id_report = $RGWeb->real_escape_string($id_report);
      $username = $RGWeb->getUsername();
      $query = "INSERT INTO `reporter_notes` (`ID` ,`id_report` ,`insert_by` ,`date` ,`text`) VALUES (NULL , '$id_report', '$username', NULL , '$text')";
      $excute = $RGWeb->runQueryMysql($query);
      // Log
      $RGWeb->addLogs("Note added for report #{$id_report}, text \"{$text}\"");
   }
}
