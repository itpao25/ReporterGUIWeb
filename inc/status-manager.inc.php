<?php
/**
*  This file is a class of ReporterGUI
*
*  @author itpao25
*  ReporterGUI Web Interface Copyright (C) 2015
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

/**
*  Gestione degli stati delle segnalazioni
*  del nuovo aggiornamento 1.8.1.1
*  Management status of reports
*/
if (!defined('RG_ROOT')) die();
Class _RGReportStatus {

   public $STATUS_APPROVED = "APPROVED";
	public $STATUS_DECLINED = "DECLINED";
	public $STATUS_OPEN = "OPEN";
	public $STATUS_DUPLICATE = "DUPLICATE";
	public $STATUS_EXPIRED = "EXPIRED";

   /**
   * Convert string status of report
   * replace 2 with Complete
   * replace 1 with waiting
   * @param st = int for status of report
   */
   public function convertStatusString($st, $ret = null) {
      global $RGWeb;
      switch ($st) {
         // Stato approvato
         case $this->STATUS_APPROVED:
            if($ret == null) :
               print '<span id="statusreport" style="font-weight: bold;color: #5CB85C">'.$RGWeb->getLang("status-approved", "ret") .'</span>';
            elseif ($ret == "ret"):
               return '<span id="statusreport" style="font-weight: bold;color: #5CB85C;">'.$RGWeb->getLang("status-approved", "ret") .'</span>';
            endif;
            break;
         // Stato rifiutato
         case $this->STATUS_DECLINED:
            if($ret == null) :
               print '<span id="statusreport" style="font-weight: bold;color: #d9534f">'.$RGWeb->getLang("status-declined", "ret") .'</span>';
            elseif ($ret == "ret"):
               return '<span id="statusreport" style="font-weight: bold;color: #d9534f">'.$RGWeb->getLang("status-declined", "ret") .'</span>';
            endif;
            break;
         // Stato aperto
         case $this->STATUS_OPEN:
            if($ret == null) :
               print '<span id="statusreport" style="font-weight: bold;color: #77003C">'.$RGWeb->getLang("status-opened", "ret") .'</span>';
            elseif ($ret == "ret"):
               return '<span id="statusreport" style="font-weight: bold;color: #77003C">'.$RGWeb->getLang("status-opened", "ret") .'</span>';
            endif;
            break;
         // Stato duplicato
         case $this->STATUS_DUPLICATE:
            if($ret == null) :
               print '<span id="statusreport" style="font-weight: bold;color: #FFCC11">'.$RGWeb->getLang("status-duplicated", "ret") .'</span>';
            elseif ($ret == "ret"):
               return '<span id="statusreport" style="font-weight: bold;color: #FFCC11">'.$RGWeb->getLang("status-duplicated", "ret") .'</span>';
            endif;
            break;
         case $this->STATUS_EXPIRED:
            if($ret == null) :
               print '<span id="statusreport" style="font-weight: bold;color: #C20021">'.$RGWeb->getLang("status-expired", "ret") .'</span>';
            elseif ($ret == "ret"):
               return '<span id="statusreport" style="font-weight: bold;color: #C20021">'.$RGWeb->getLang("status-expired", "ret") .'</span>';
            endif;
            break;
         default:
            return $st;
      }
   }

   /**
   * Function for edit report
   * @param id -> Id del report che deve essere modifcato
   * @param action -> Azione che deve essere eseguita per modificare il report
   * @since 1.6
   */
   public function editReport($id, $action)
   {
      global $RGWeb;
      
      // Check report is exist
      if( $RGWeb->isReportExist( $id ) == false ) {
         return;
      }
      // Check user is not in group "helper"
      if( $RGWeb->getGroup->isHelper() == true ) {
         return;
      }
      // Sql injection security
      $id = $RGWeb->real_escape_string($id);
      switch ($action) {
         case 'approve':
         case $RGWeb->getStatusManager->STATUS_APPROVED:
            $sql_query = $RGWeb->runQueryMysql("UPDATE `reporter` SET status='{$RGWeb->getStatusManager->STATUS_APPROVED}' WHERE ID={$id}");
            break;
         case 'decline':
         case $RGWeb->getStatusManager->STATUS_DECLINED:
            $sql_query = $RGWeb->runQueryMysql("UPDATE `reporter` SET status='{$RGWeb->getStatusManager->STATUS_DECLINED}' WHERE ID={$id}");
            break;
         case 'open':
         case $RGWeb->getStatusManager->STATUS_OPEN:
            $sql_query = $RGWeb->runQueryMysql("UPDATE `reporter` SET status='{$RGWeb->getStatusManager->STATUS_OPEN}' WHERE ID={$id}");
            break;
         case 'duplicate':
         case $RGWeb->getStatusManager->STATUS_DUPLICATE:
            $sql_query = $RGWeb->runQueryMysql("UPDATE `reporter` SET status='{$RGWeb->getStatusManager->STATUS_DUPLICATE}' WHERE ID={$id}");
            break;
         case 'expire':
         case $RGWeb->getStatusManager->STATUS_EXPIRED:
            $sql_query = $RGWeb->runQueryMysql("UPDATE `reporter` SET status='{$RGWeb->getStatusManager->STATUS_EXPIRED}' WHERE ID={$id}");
            break;
         default:
            return;
            break;
      }
   }

   /**
   * This function allows to print the html of select
   * @since 1.6.4
   */
   public function getSelectStatus($id_report, $stato) {
      global $RGWeb;
      // List of all status
      $array = array(
         $RGWeb->getStatusManager->STATUS_APPROVED,
         $RGWeb->getStatusManager->STATUS_DECLINED,
         $RGWeb->getStatusManager->STATUS_OPEN,
         $RGWeb->getStatusManager->STATUS_EXPIRED,
         $RGWeb->getStatusManager->STATUS_DUPLICATE
      );
      // Start to print the form in html
      print "<form id=\"report-focus-editstatus\" class=\"report-focus-editstatus\"><select name=\"changestatus-report\" >";
      foreach($array as $key) {
         // same field in html select, then add selected attr
         if($key == $stato) {
            echo "<option value=\"$key\" selected =\"selected\" > $key </option>";
         } else {
            echo "<option value=\"$key\"> $key </option>";
         }
      }
      print "</select>";
      print "<input value=\"{$RGWeb->getLang('report-focus-editstatus-save','ret')}\" type=\"submit\" /></form>";
   }
}
?>
