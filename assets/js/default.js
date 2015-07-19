/* 	ReporterGUI Web Interface  Copyright (C) 2015  itpao25

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License as
	published by the Free Software Foundation; either version 2 of
	the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*/
function getMenuItem(Pos) {
	$("#menu-"+ Pos +"").addClass("active");
}

function redirect(url, time) {
	setTimeout(function() {
		window.location.href = url;
	}, time);

}
$( document ).ready(function() {
	
	$('tr[data-href]').on("click", function() {
		document.location = $(this).data('href');
	});
});
