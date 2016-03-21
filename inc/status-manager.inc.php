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
}
?>
