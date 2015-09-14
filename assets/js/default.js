/* 	ReporterGUI Web Interface  Copyright (C) 2015  itpao25 */

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
// Change title of page (using tag tite)
function cambiatitolo($str) {
	$("title").text($str);
}
// Change status of report in page (for ajax request)
function cambioStatoReport(status, text)
{
	switch (status) {
		case 1:
			$("#statusreport").attr("style", "font-weight: bold;color: #d9534f;");
			break;
		case 2:
			$("#statusreport").attr("style", "font-weight: bold;color: #5CB85C;");
			$(".bottone-editstatus").fadeOut();
			break;
	}
	$("#statusreport").text(text);
}
function securityXSS() {
	console.log('%cStop! ', 'font-size: 30px; color: #bada55');
	console.log('%cThe use of the console can be risky for the security of your account! ', 'font-size: 15px; color: #bada55');
}
securityXSS();
