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
function securityXSS() {
	console.log('%cStop! ', 'font-size: 30px; color: #bada55');
	console.log('%cThe use of the console can be risky for the security of your account! ', 'font-size: 15px; color: #bada55');
}
securityXSS();

// Generate unique ids client-side
var c = 1;
function unique_id() {
	var date = new Date(),
	millisecond = date.getMilliseconds() + "",
	u = ++date + millisecond + (++c === 10000 ? (c = 1) : c);
	return u;
}
