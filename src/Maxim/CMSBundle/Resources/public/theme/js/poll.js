$(document).ready(function() {
	$("#frmPoll").submit(function() {
		//get the url for the form
		AJAX.post(($("#frmPoll").attr("action")), {_rdbAnswer : $('input[name=_rdbAnswer]:checked', '#frmPoll').val(),}, $(".pnlPoll-content #msgbox"), $("#btnfrmPoll"));
		//we dont what the browser to submit the form
		return false;
	});
});

