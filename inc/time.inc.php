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

/**
* Classe per la gestione del Time nel pannello di ReporterGUIWeb
* @since 1.6.2
*/
Class _RGTimeManager {

   /**
   * Gestisco il tempo fa, quindi dopo aver convertito la stringa in unix time
   * provo a trovare la differenza del tempo
   * @since 1.6.2
   * @return string time ago
   */
   public function timeago($time) {
      // Converto in unix
      $time = strtotime($time);
      $estimate_time = time() - $time;
      // Cerco la differenza
      if( $estimate_time < 1 ) {
         return 'less than 1 second ago';
      }
      $condition = array(
         12 * 30 * 24 * 60 * 60  =>  'year',
         30 * 24 * 60 * 60       =>  'month',
         24 * 60 * 60            =>  'day',
         60 * 60                 =>  'hour',
         60                      =>  'minute',
         1                       =>  'second'
      );
      foreach( $condition as $secs => $str ) {
         $d = $estimate_time / $secs;
         if( $d >= 1 ) {
            $r = round( $d );
            return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
         }
      }
   }

   /**
   * Ritorno con il tempo attuale
   * Usando le impostazione dal config
   * @since 1.6.2
   * @return timestamp new
   */
   public function getCurrentTime() {
      global $RGWeb;

      $format = $RGWeb->getConfig("data-format") != null || $RGWeb->getConfig("data-format") != "" ? $RGWeb->getConfig("data-format") : "Y-m-d H:i:s";
      $data = date($format);
      return $data;
   }
}
