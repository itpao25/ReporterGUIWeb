<?php

#  ReporterGUI WebInterface 1.0
#  GNU License - itpao25
#  https://www.spigotmc.org/resources/reportergui.8596/
if (!defined('RG_ROOT')) die("This file can not be opened in this way");

# Change only the first time!
$config['installed-sec'] = false;

# Mysql management
$config['mysql-host'] = "localhost";
$config['mysql-port'] = 3306;
$config['mysql-user'] = "root";
$config['mysql-password'] = "";
$config['mysql-databaseName'] = "reportergui";

/* Do not touch the version please */
$config['versions'] = "1.6";

# Server Name
$config['nameServer'] = "NewMcStory";

# Title for each page ([title] - ReporterGUI Web Interface) for example
$config['pageName'] = "ReporterGUI Web Interface";

# Custom server logo (insert URL with http / https)
$config['customLogo'] = "";

# Service for avatar attach
# Use variable {username} for get name of the player
$config['urlServiceAvatar'] = "https://crafatar.com/renders/head/{username}";

# Service for avatar in menu
$config['urlServiceAvatarMenu'] = "https://crafatar.com/avatars/{username}";

# Notify system
# Ajax time for check (millisecond)
# 10000 = 10 seconds
# 20000 = 20 seconds ecc..
$config['notify-request'] = 10000;

?>
