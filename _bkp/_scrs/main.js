// =======================
// 	Main Driver 
// =======================

$(document).ready(function() {
	resize_user_activity_section(); 
	load_menu(); 
	hide_show_menu(); 
	premake_svgs(); 
	handle_user(); 
	bake_pie(); 
	focus_regions(); 
	activate_region_buttons(); 
	activate_navbar_menu(); 
	hide_sections(); 

	// choose item from menu 
	$('.subnav .menuoptions li').on('click',function() { 
		// $('.subnav .me li#'+SELECTED).removeClass('selected'); 

		var id = $(this).attr('id'); 
		// SELECTED = id; 
		// $('.usermenu li#'+SELECTED).addClass('selected'); 

		id = id.substring(1,id.length); 
		// console.log(id); 
		$.ajax({
			type: 'GET', 
			// url: 'testloader.php', 
			url: 'http://environtracker.benova.net/_api/requests.php', 
			data: {
				action:'pull', 
				id:id 
			}, 
			async: false, 
			success: function(data) {
				var j = $.parseJSON(data);  
				if(j.hasOwnProperty("success")) {
					session = j["data"][id]; 
					display_data(); 
					if(selected_visualization==1) draw_heatmap(); 
					else draw_points(); 
				} else { 
					console.log("error"); 
					console.log(j); 
				}
			}, error: function() { 
				console.log("error"); 
			}

		}); 
	}); 

	// select map visualization                                                                                                                                                                              
	$('.option').on('click',function(e) {
        var id = $(this).attr('id');
        switch(id) {
            case 'points':
                draw_points();
                break;
            case 'heatmap':
                draw_heatmap();
				break;
            case 'cluster':
                draw_cluster();
                break;
            default: break;
        }
    });
}); 

$(window).resize(function() {
	resize_user_activity_section(); 
}); 


// ===========
// 	Functions 
// ===========

// resizing activity section 
function resize_user_activity_section() {
	if(window.innerWidth>=1500) {
		$('nav').css({'left':'0px'}); 
		$('.hidemenuoption').css({'visibility':'hidden'}); 
	} else { 
		$('nav').css({'left':'-270px'}); 
		$('.hidemenuoption').css({'visibility':'visible','left':'0px'}).html('&raquo;'); 
	}

	// $('.activity').css({
	// 	'width':(window.innerWidth-290)+'px' 
	// }); 
} 

function load_menu() {
	$.ajax({
		type: 'GET', 
		url: 'http://environtracker.benova.net/_api/requests.php', 
		data: {
			action:'options'
		}, 
		async:false, 
		success: function(data) {
			var j = $.parseJSON(data); 
			if(j.hasOwnProperty("success")) {
				for(var i=0; i<j["data"].length; i++) {
					var val = j["data"][i]; 
					$('.subnav .menuoptions').append('<li id="u'+val+'">'+val+'</li>'); 
				}
			}
		}, 
		error: function(data) {
			console.log('something went wrong, check back later.'); 
		}
	})
}

// hide/show menu depending on screen size 
function hide_show_menu() {
	$('.hidemenuoption').on('click',function() {
		var x = $(this).offset().left; 
		if(x<10) {
			$('.hidemenuoption').animate({left:'270px'}, 500, function() {}).html('&laquo;'); 
			$('nav').animate({left:'0px'}, 500, function() {}); 
		} else {
			$('.hidemenuoption').animate({left:'0px'}, 500, function() {}).html('&raquo;'); 
			$('nav').animate({left:'-270px'}, 500, function() {}); 
		}
	}); 
}

function premake_svgs() {
	// draw score graphs 
	ENVIRON = d3.select(".score#environ .graphplot").append('svg') 
				.attr('width',gw) 
				.attr('height',gh); 
	ECONOMY = d3.select(".score#economy .graphplot").append('svg') 
				.attr('width',gw) 
				.attr('height',gh); 

	// make an svg container 
	SVG = d3.select('.game .grid').append('svg')
			.attr('width',w) 
			.attr('height',h); 

	// draw invisible grid 
	var j = -1; 
	GRID = d3.range(dx*dy).map(function(i) {
		var newx = (i*rx)%w; 
		if(newx==0) j++; 
		var newy = ry*j; 

		var b = new Block(); 
		b.instantiate(i,SVG,newx,newy,rx,ry); 
		return b; 
	}); 
}

function handle_user() {
	var url = "http://localhost:8888/EnvironUITracker/"; 
	$('#login').on('click',function() {
		var u = $('#uname').val(), 
			p = $('#pword').val(); 
		$.ajax({
			type:'POST', 
			url:'http://environtracker.benova.net/_incs/private_requests.php', 
			data: {
				action:'login', 
				uname:u, 
				pword:p 
			}, 
			async: false, 
			success: function(data) {
				var j = $.parseJSON(data); 
				if(j.hasOwnProperty("success")) 
					window.location.replace(url); 
				else {
					$('input#uname,input#pword').css({
						'border':'1px solid #dc5151'
					}); 
					console.log(j["message"]); 
				}
			}
		}); 
	});

	$('#uname,#pword').on('keyup',function(e) {
		if(e.which=='13') {
			var u = $('#uname').val(), 
				p = $('#pword').val(); 
			$.ajax({
				type:'POST', 
				url:'http://environtracker.benova.net/_incs/private_requests.php', 
				data: {
					action:'login', 
					uname:u, 
					pword:p 
				}, 
				async: false, 
				success: function(data) {
					var j = $.parseJSON(data); 
					if(j.hasOwnProperty("success")) 
						window.location.replace(url); 
					else console.log(j["message"]); 
				}
			}); 
		}
	}); 

	$('#logout').on('click',function() {
		console.log("signing out 1"); 
		$.ajax({
			type:'POST', 
			url:'http://environtracker.benova.net/_incs/private_requests.php', 
			data: { action:'logout' }, 
			async: false, 
			success: function(data) {
				window.location.replace(url); 
				console.log("signing out 2"); 
			}
		}); 
	}); 
}

function activate_navbar_menu() {
	$('.mainnav .menuoptions li').on('click',function() {
		$('.mainnav .menuoptions li').removeClass('selected'); 
		$(this).addClass('selected'); 
	}); 

	$('.subnav .menuoptions li').on('click',function() {
		$('.subnav .menuoptions li').removeClass('selected'); 
		$(this).addClass('selected'); 
	}); 
}