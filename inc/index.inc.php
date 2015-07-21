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
<div class="container">
	<h2 style="border-bottom: 1px dashed #808080;">
		Latest reports
	</h2>
	<?php $this->getListIndex(); ?>
	<br />

	<h2 style="border-bottom: 1px dashed #808080;">
		<i class="fa fa-line-chart"></i> Stats
	</h2>
	<div class="row">
		<div class="colonna_50">
			<b>Total server registered:</b> <?= $this->getIntTotalServer(); ?><br />
		</div>
		<div class="colonna_50">
		
		</div>
	</div>
</div>
