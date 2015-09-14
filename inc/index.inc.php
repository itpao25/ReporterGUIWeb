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
if (!defined('RG_ROOT_INDEX')) die();


?>
<h2 class="home-titlepage">
	<?php $this->getLang("home-titlepage"); ?>
	<span>
		<a href="view-report.php"><?php $this->getLang("home-titleallreport") ?></a>
	</span>
</h2>
<?php $this->getListIndex(); ?>
<br />
<!-- stats -->
<h2 style="border-bottom: 1px dashed #808080;">
	<i class="fa fa-line-chart"></i> <?php $this->getLang("home-titlestats"); ?>
</h2>
<div class="row">
	<div class="colonna_50">
		<b style="font-size: 22px;" ><?php $this->getLang("home-stats-totserver"); ?></b> <?php echo $this->getIntTotalServer(); ?><br />
		<b><?php $this->getLang("home-stats-totreport"); ?> </b><?php echo $this->getIntTotalReport(); ?> <br />
		<b><?php $this->getLang("home-stats-totreportwa"); ?> </b><?php echo $this->getIntTotalReportWaiting(); ?> <br />
		<b><?php $this->getLang("home-stats-totreportco"); ?> </b><?php echo $this->getIntTotalReportComplete(); ?> <br />
	</div>
	<div class="colonna_50">
		<?php
		if($this->getGroup->isAdmin() || $this->getGroup->isModerator()) {
			print "<b style=\"font-size: 22px\"><i style=\"font-size: 60px;float: right;color: #7F7F7F;\" class=\"fa fa-users\"></i> Users registred: </b>". $this->getGroup->getNumUser(). "<br />";
			print "<b>". $this->getLang("tag-admin", "ret") . ":</b> {$this->getGroup->getNumUserAdmin()} <br />";
			print "<b>". $this->getLang("tag-moderator", "ret") . ":</b> {$this->getGroup->getNumUserMod()} <br />";
			print "<b>". $this->getLang("tag-helper", "ret") . ":</b> {$this->getGroup->getNumUserHelper()} <br />";
		}
		?>
	</div>
</div>
