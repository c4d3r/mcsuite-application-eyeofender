/*
$(function() {
	$("a[rel='tab']").click(function(e) {
		//e.preventDefault();
		/*
		if uncomment the above line, html5 nonsupported browers won't change the url but will display the ajax content;
		if commented, html5 nonsupported browers will reload the page to the specified link.
		
		
		$('#Content-Mid').html('<div id="Content-Mid"><div class="pnlHeader-Large"><h1 class="color-title FloatLeft">Loading page...</h1></div><div class="News-Content">Loading your page....</div></div>');
		//get the link location that was clicked
		pageurl = $(this).attr('href');

		//to get the ajax content and display in div with id 'content'
		$.ajax({
			url : pageurl,
			success : function(data) {
				var data2 = $(data).find('#Content-Mid');
				$('#Content-Mid').html($(data2).html());
			}
		});

		//to change the browser URL to 'pageurl'
		if (pageurl != window.location) {
			window.history.pushState({
				path : pageurl
			}, '', pageurl);
		}
		return false;
	});
	/* the below code is to override back button to get the ajax content without reload

});
*/

