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
Class _RGroups
{
  public function getMenuGroup() {

    $group = $this->getGroupLogged();
    global $RGWeb;

    switch ($group) {
      case 'admin':
        print "<a id=\"menu-Users\" href=\"users.php\">{$RGWeb->getLang("menu-manuser", "ret")}</a>";
        print "<a id=\"menu-Update\" href=\"update.php\">{$RGWeb->getLang("menu-update", "ret")}</a>";
        break;
      case 'moderator':
        print "<a id=\"menu-Users\" href=\"users.php\">{$RGWeb->getLang("menu-manuser", "ret")}</a>";
        print "<a id=\"menu-Update\" href=\"update.php\">{$RGWeb->getLang("menu-update", "ret")}</a>";
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
    $query = $RGWeb->runQueryMysql("SELECT * FROM `webinterface_login` ORDER BY `webinterface_login`.`ID` ASC");
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
  * @return [4] = Group
  * @return [5] = ifNotify
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

    $query = $RGWeb->runQueryMysql("SELECT username,ID,lastlogin,permission,lastIP,permission  FROM `webinterface_login` WHERE ID={$iden}");
    $queryArray = mysqli_fetch_assoc($query);

    $id = $queryArray['ID'];
    $username = $queryArray['username'];
    $lastlogin = $queryArray['lastlogin'];
    $lastIP = $queryArray['lastIP'];
    $group = $queryArray['permission'];
    $lastIp = $RGWeb->getNotify->isNotifyEnable();

    return array ($id, $username, $lastlogin, $lastIP, $group, $lastIp);
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

  /**
  * Get select html for page edit user
  */
  public function getSelectOptionEdit($user) {
    global $RGWeb;

    $options = array('admin', 'moderator', 'helper');
    $selected = $RGWeb->getGroupUser($user);

    echo "<select name=\"editUser-group\" >";
    foreach($options as $key)
    {
      if($selected == $key):
        echo "<option value=\"$key\" selected =\"selected\"> $key </option>";
      else:
        echo "<option value=\"$key\"> $key </option>";
      endif;
    }
    echo "</select>";
  }

  /**
  * Changepassword for user, important function
  */
  public function changePassword($id, $password)
  {
    global $RGWeb;
    if($RGWeb->getGroup->isAdmin() == false) {
      retun;
    }
    $password = $RGWeb->real_escape_string($password);

    // Password crypt
    $salt = "w\|KT!jc@sn/@h//X";
    $passwordCRYPT = hash('sha512', $password.$salt);

    $id = $RGWeb->real_escape_string($id);

    if($id != 1 || $id == 1 && $RGWeb->getIDLogged() == 1):
      $query = $RGWeb->runQueryMysql("UPDATE webinterface_login SET password='{$passwordCRYPT}' WHERE ID='{$id}'");
      print "<br /><div class='container messaggio-success'>{$RGWeb->getLang("action-edituser-success", "ret")}</div>";
    else:
      print "<br /><div class='container messaggio-errore'>{$RGWeb->getLang("action-edituser-noperm", "ret")}</div>";
    endif;

  }

  /**
  * Changegroup for user, important function
  */
  public function changeGroup($id, $group)
  {
    global $RGWeb;
    if($RGWeb->getGroup->isAdmin() == false) {
      retun;
    }
    $group = $RGWeb->real_escape_string($group);
    $id = $RGWeb->real_escape_string($id);

    if($id != 1 || $id == 1 && $RGWeb->getIDLogged() == 1):
      $query = $RGWeb->runQueryMysql("UPDATE webinterface_login SET permission='{$group}' WHERE ID={$id}");
      print "<br /><div class='container messaggio-success'>{$RGWeb->getLang("action-edituser-success", "ret")}</div>";
    else:
      print "<br /><div class='container messaggio-errore'>{$RGWeb->getLang("action-edituser-noperm", "ret")}</div>";
    endif;
  }

  /**
  * Introducing for user-server
  */
  public function isUserServerID($id) {
    
  }


}
?>
