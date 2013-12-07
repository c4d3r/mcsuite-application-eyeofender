function mainmenu(){
		$('#Navigation ul').css({display: 'none'}); // Opera Fix
		$('#Navigation li').hover(function(){
				$(this).find('ul:first').css({visibility: 'visible',display: 'none'}).show(300);
				},function(){
				$(this).find('ul:first').css({visibility: 'hidden'});
				});
		}
		 $(document).ready(function(){
			mainmenu();
		});