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
  die();
}

/**
* Check user logged is admin
*/
if($RGWeb->getGroup->isAdmin() == true) {

  /* Add server through post request */
  if(isset($_POST['name-user-add']) && isset($_POST['pass-user-add']) &&
  isset($_POST['pass2-user-add']) && isset($_POST['group-user-add']))
  {
    if($_POST['pass-user-add'] == $_POST['pass2-user-add']) {
      $send = $RGWeb->addUsers($_POST['name-user-add'], $_POST['pass-user-add'], $_POST['group-user-add']);
      die();
    } else
    {
      die("The passwords do not match!");
    }
  }
  $RGWeb->getHeader("Users");

} else
{
  $RGWeb->getHeader("Users");
  /* User logged is not admin */
  die($RGWeb->getUtily->messageNoPermission());
}
?>
<h2 style="border-bottom: 1px dashed #808080;"><i class="fa fa-plus"></i> Add user</h2>

<div class="row">
  <div class="colonna_50">
    <div id="messaggio-add" ></div>
    <form id="add-user" action="add-user.php" class="add-server" method="post">
      <input type="text" name="name-user-add" placeholder="Username" />
      <input type="password" name="pass-user-add" placeholder="Password" />
      <input type="password" name="pass2-user-add" placeholder="Repeat password" />
      <select name="group-user-add">
        <option value="admin">Admin</option>
        <option value="moderator">Moderator</option>
        <option value="helper">Helper</option>
      </select>
      <input type="submit" name="submit-server-add" value="Create user" />
    </form>
  </div>
  <div class="colonna_50">
  </div>
</div>

<!-- use ajax for send a request -->
<script type="text/javascript">
  $("#add-user").submit(function() {
    var url = "add-user.php";
      $.ajax({
         type: "POST",
         url: url,
         data: $("#add-user").serialize(),
         success: function(data)
         {
           $("#messaggio-add").show();
           if(data.trim() == "User successfully added!")
           {
             if($("#messaggio-add").hasClass("messaggio-errore")){
               $("#messaggio-add").removeClass("messaggio-errore");
             }
             $("#messaggio-add").addClass("messaggio-success");

             redirect("users.php", 1000);

           } else {

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
