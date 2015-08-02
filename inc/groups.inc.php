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
Class RGroups
{
  public function getMenuGroup() {

    $group = $this->getGroupLogged();
    switch ($group) {
      case 'admin':
        print "<a id=\"menu-Users\" href=\"users.php\">Manager users</a>";
        print "<a id=\"menu-Update\" href=\"update.php\">Update</a>";
        break;
      case 'moderator':
        print "<a id=\"menu-Users\" href=\"users.php\">Manager users</a>";
        print "<a id=\"menu-Update\" href=\"update.php\">Update</a>";
      default:
        # code...
        break;
    }
  }

  /**
  * Get ID of group user logged in
  * @return int id
  */
  public function getGroupID($group = null)
  {
    if($group == null) $group = $this->getGroupLogged();
    switch ($group)
    {
      // Group admin
      case 'admin':
        return 3;
        break;
      // Group moderator
      case 'moderator':
        return 2;
        break;
      // Group helper
      case 'helper':
        return 1;
        break;
      // Error in row o in query
      default:
        return 0;
        break;
    }
  }
  /**
  * Get group user logged
  * Using session username
  */
  public function getGroupLogged() {

    global $RGWeb;
    $username = $RGWeb->real_escape_string($_SESSION['rg_username']);
    $query = $RGWeb->runQueryMysql("SELECT username, permission FROM `webinterface_login` WHERE username='{$username}'");
    $row = mysqli_fetch_assoc($query);

    /* Trim user group for fix to edit*/
    return trim($row['permission']);
  }

  /**
  * Lista degli utenti presenti nella tabella webinterface_login
  */
  public function getListUsers() {

    global $RGWeb;
    $query = $RGWeb->runQueryMysql("SELECT * FROM `webinterface_login`");
    print "<table style=\"width:100%\">
      <thead>
        <tr>
          <td><b>ID</b></td>
          <td><b>Username</b></td>
          <td><b>Group</b></td>
          <td><b>Last login</b></td>
          <td><b>Last IP</b></td>
        </tr>
      </thead>";

    while($row = $query->fetch_array())
    {
      print "
      <tr class=\"list-server-table\" data-href=\"manager-user.php?id={$row['ID']}\">
        <td>{$row['ID']}</td>
        <td>{$row['username']}</td>
        <td>{$row['permission']}</td>
        <td>{$row['lastlogin']}</td>
        <td>{$row['lastIP']}</td>
      </tr>";
    }

    print "</table>";
  }

  /**
  * Check user exist
  * @param username
  */
  public function isUserExits($username) {

    global $RGWeb;
    $name = $RGWeb->real_escape_string($username);
    $query = $RGWeb->runQueryMysql("SELECT username FROM `webinterface_login` WHERE username={$name}");
    return $query->num_rows >= 1 ? true : false;

  }

  /**
  * Check user id exist
  * @param username
  */
  public function isUserIDExits($id) {

    global $RGWeb;
    $iden = $RGWeb->real_escape_string($id);
    $query = $RGWeb->runQueryMysql("SELECT ID FROM `webinterface_login` WHERE ID={$iden}");
    if($query->num_rows == 0) {
      return false;
    } else {
      return true;
    }
    //return $query->num_rows == 1 ? true : false;
  }

   /**
  * Check user logged is admin
  */
  public function isAdmin() {
    if($this->getGroupLogged() == "admin"):
      return true;
    endif;
    return false;
  }

  /**
  * Check user logged is moderator
  */
  public function isModerator() {
    if($this->getGroupLogged() == "moderator"):
      return true;
    endif;
    return false;
  }

  /**
  * Check user logged is helper
  */
  public function isHelper() {
    if($this->getGroupLogged() == "helper"):
      return true;
    endif;
    return false;
  }

  /**
  * Get information for user
  * @param id user
  * @return array details
  * @return [0] = id
  * @return [1] = username
  * @return [2] = lastlogin
  * @return [3] = lastIP
  */
  public function getDetailsUser($id, $check = null)
  {
    if($check == true):
      if($this->isUserIDExits($id) == false):
        return false;
      endif;
    endif;
    global $RGWeb;
    $iden = $RGWeb->real_escape_string($id);

    $query = $RGWeb->runQueryMysql("SELECT username,ID,lastlogin,permission,lastIP  FROM `webinterface_login` WHERE ID={$iden}");
    $queryArray = mysqli_fetch_assoc($query);

    $id = $queryArray['ID'];
		$username = $queryArray['username'];
		$lastlogin = $queryArray['lastlogin'];
		$lastIP = $queryArray['lastIP'];

    return array ($id, $username, $lastlogin, $lastIP);
  }

  /**
  * Get number of user registred
  */
  public function getNumUser() {
    global $RGWeb;
    $query = $RGWeb->runQueryMysql("SELECT ID FROM `webinterface_login`");
    return $query->num_rows;
  }

  /**
  * Get number of user with group admin
  */
  public function getNumUserAdmin() {
    global $RGWeb;
    $query = $RGWeb->runQueryMysql("SELECT permission FROM `webinterface_login` WHERE permission='admin'");
    return $query->num_rows;
  }

  /**
  * Get number of user with group mod
  */
  public function getNumUserMod() {
    global $RGWeb;
    $query = $RGWeb->runQueryMysql("SELECT permission FROM `webinterface_login` WHERE permission='moderator'");
    return $query->num_rows;
  }

  /**
  * Get number of user with group heler
  */
  public function getNumUserHelper() {
    global $RGWeb;
    $query = $RGWeb->runQueryMysql("SELECT permission FROM `webinterface_login` WHERE permission='helper'");
    return $query->num_rows;
  }


}

$RGroups = new RGroups();
