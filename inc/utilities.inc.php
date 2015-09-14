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

/**
*  Gestione delle utilità per il sistema
*  Management of utilities for system
*/
if (!defined('RG_ROOT')) die();

Class _RGUtilities
{
	/**
	* Dupicate function get config data
	*/
	private function getConfig($conf, $pos = null)
	{
		/* Check the position of the file */
		if($pos == "root" || $pos == null)
			include("config.php");
		elseif($pos == "Dir")
			include(dirname(dirname(__FILE__)).'/config.php');
		return $config[$conf];
	}
	/**
	* Message for login success
	* @return html
	*/
	public function loginSuccess() {

		echo "<script type='text/javascript'>
			if ( $( '.messaggio-errore' ).length ) {
				$( '.messaggio-errore' ).remove();
			}
		</script>";

		echo "<div class='container messaggio-success'>You logged in successfully!</div>";
		echo "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
	}
	/**
	* Message for login error
	* @return html
	*/
	public function loginError() {
		echo "<script type='text/javascript'>
			if($('.login-contenitore > img').hasClass('pulse')) {
				$('.login-contenitore > img').removeClass('pulse');
				$('.login-contenitore > img').addClass('shake');
			}
		</script>";
		echo "<div class='container messaggio-errore'>Invalid password or username!</div>";
	}
	/**
	* Message for logout success
	*/
	public function logoutSuccess() {
		echo "<div class='container messaggio-success'>You have logged out successfully!</div>";
	}

	/* Messaggio che viene stampato in caso nel config l'array $config['installed-sec'] è su false  */
	public function messageInstall() {
		print "<!DOCTYPE html><html><title>Install ReporterGUI</title>";
		print "<link href=\"install/install.css\" rel=\"stylesheet\">";
		print "<img width=\"320px\" src=\"assets/img/logo-rgui.png\" />";
		print "<h4 style=\"margin: 0px\">Follow these instructions to install ReporterGUI:</h4><br />";
		print "<h5 style=\"margin: 0px\">First step:</h5>";
		print "<ul><li>Open the file config.php</li>";
		print "<li>Find the string <i>&#36;config ['installed-sec'] = false;</i></li>";
		print "<li>Replace <i>false</i> with <i>true</i>";
		print "<li>Set the setting for the database mysql</li>";
		print "<li>Save the file and refresh this page</li></ul></html>";
		die();
	}

	public function messageInstallFolder() {
		print "<!DOCTYPE html><html><title>Install ReporterGUI</title>";
		print "<link href=\"install/install.css\" rel=\"stylesheet\">";
		print "<img width=\"320px\" src=\"assets/img/logo-rgui.png\" />";
		print "<h4 style=\"margin: 0px\">You have completed the installation, now delete the folder <i>install</i></h4><br /></ul>Good fun!</html>";
		die();
	}
  /**
  * Get the ip adress client
  * @return IP Adress
  */
  public function getIndirizzoIP() {
    return $_SERVER['REMOTE_ADDR'];
  }

	/**
  * Get the useragent client
  * @return string http agent
  */
	public function getAgent() {
		return $_SERVER['HTTP_USER_AGENT'];
	}
  /**
  * Get current year
  * @return year
  */
  public function getCurrentYear() {
    return date("Y");
  }
  /**
  * Get name of server
  * @return name
  */
  public function getNameServer() {
    return $this->getConfig("nameServer","Dir");
  }

  /**
  * Get avatar user using default CRAFATAR.com
  * You can customize the service for avatars
  *
  * @param nickname Nickname of the player
  * @return url service
  */
  public function getAvatarUser($nickname) {
    return $this->getUrlServiceAvatar($nickname);
  }

	/**
	* Get service url from config
	* Check string is isset, then replace variable {username} to nickname
	*
	* @param nickname Nickname of the player
	* @return url service
	*/
	public function getUrlServiceAvatar($username)
	{
		// Check string in config is isset
		if(!$this->getConfig("urlServiceAvatar")):
			return "https://crafatar.com/avatars/". $username;
		else:
			return str_replace("{username}", $username ,$this->getConfig("urlServiceAvatar"));
		endif;

	}

	/**
	* Get service url from config for
	* menu
	* @param username
	*/
	public function getUrlServiceAvatarMenu($username)
	{
		if(!$this->getConfig("urlServiceAvatarMenu")):
			return "https://crafatar.com/avatars/". $username;
		else:
			return str_replace("{username}", $username ,$this->getConfig("urlServiceAvatarMenu"));
		endif;
	}

	/* Get custom logo */
	public function getLogo() {

		if(!trim($this->getConfig("customLogo")) == ""):
			$url = $this->getConfig("customLogo");
			return $url;
		else:
			return "assets/img/logo-rgui.png";
		endif;
	}
	/**
	* Display error no permission for view page
	*/
	public function messageNoPermission() {
		echo "<br /><div class=\"container messaggio-errore\">You do not have access to view this page!</div>";
	}

	/**
	* Display error for user not exists
	*/
	public function userNotExists() {
		echo "<br /><div class=\"container messaggio-errore\">This user does not exist!!</div>";
	}

	/**
	* Get current url
	*/
	public function selfURL()
	{
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}
  public function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}

	/**
	* Clean special characters in string
	* @param string
	* @return string without special characters
	* @since 1.5
	*/
	function clean($string) {
		$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}

	/**
	* Convert string status of report
	* replace 2 with Complete
	* replace 1 with waiting
	* @param st = int for status of report
	*/
	public function convertStatusString($st, $ret = null) {
		global $RGWeb;
		switch ($st) {
			case 1:
				if($ret == null) :
					print '<span id="statusreport" style="font-weight: bold;color: #d9534f">'.$RGWeb->getLang("status-wating", "ret") .'</span>';
				elseif ($ret == "ret"):
					return '<span id="statusreport" style="font-weight: bold;color: #d9534f">'.$RGWeb->getLang("status-wating", "ret") .'</span>';
				endif;
				return;
				break;
			case 2:
				if($ret == null) :
					print '<span id="statusreport" style="font-weight: bold;color: #5CB85C;">'.$RGWeb->getLang("status-complete", "ret") .'</span>';
				elseif ($ret == "ret"):
					return '<span id="statusreport" style="font-weight: bold;color: #5CB85C;">'.$RGWeb->getLang("status-complete", "ret") .'</span>';
				endif;
				return;
				break;
			default:
				return $st;
				break;
		}
	}
}
?>
