$(document).ready(function() {
	$("#frmRedeem").submit(function() {
		//get the url for the form
		AJAX.post(
			$("#frmRedeem").attr("action"), 
			{
				_ign : $('#ign').val()
			}, 
			$("#frmRedeem_status"), 
			$("#btnRedeem"));
		//we dont what the browser to submit the form
		return false;
	});
});

function fncVote(ID) {
	var vallink = $("#" + ID + "_VoteLink").val();
	//remove all the class add the messagebox classes and start fading
	$("#site_" + ID).removeClass().addClass('messagebox').text('Voting....').fadeIn(1000);
	//check the username exists or not from ajax
	var t = setTimeout("document.location='/home'", 3000)
}