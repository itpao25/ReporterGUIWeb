<?php

#  ReporterGUI WebInterface 1.0
#  GNU License - itpao25
#  https://www.spigotmc.org/resources/reportergui.8596/
if (!defined('RG_ROOT')) die("This file can not be opened in this way");

$config['installed-sec'] = true;

/* Do not touch the version please */
$config['versions'] = "1.0";

# Check update version of ReporterGUI WebInterface from github.com
$config['check-update'] = true;

$config['nameServer'] = "NewMcStory";
$config['pageName'] = "ReporterGUI Web Interface";


# Service for avatar attach
# Use variable {username} for get name of the player
$config['urlServiceAvatar'] = "https://crafatar.com/renders/head/{username}";

# Mysql management
$config['mysql-host'] = "localhost";
$config['mysql-port'] = 3306;
$config['mysql-user'] = "root";
$config['mysql-password'] = "";
$config['mysql-databaseName'] = "reportergui";



?>
