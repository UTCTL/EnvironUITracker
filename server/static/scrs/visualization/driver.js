// =======================
// 	Main Driver 
// =======================

// @TODO : change for production 
var APPURL = 'http://localhost:8888/EnvironCPI/controllers/api.php'; 

$(document).ready(function() {
	resize_user_activity_section(); 
	load_menu(); 
	hide_show_menu(); 
	premake_svgs(); 
	bake_pie(); 
	focus_regions(); 
	activate_region_buttons(); 
	activate_navbar_menu(); 
	hide_sections(); 

	// choose item from menu 
	$('body').on('click','.subnav .menuoptions li',function() { 
		// $('.subnav .me li#'+SELECTED).removeClass('selected'); 

		var id = $(this).attr('id'); 
		var sid = $(this).html(); 
		// SELECTED = id; 
		// $('.usermenu li#'+SELECTED).addClass('selected'); 
		console.log("clicked on "+sid); 

		id = id.substring(1,id.length); 
		// console.log(id); 
		$.ajax({
			type: 'GET', 
			// url: 'testloader.php', 
			// url: 'http://environtracker.benova.net/_api/requests.php', 
			url: APPURL, 
			data: {
				action:'pull', 
				id:id 
			}, 
			async: false, 
			success: function(data) {
				var j = $.parseJSON(data);  
				console.log(j); 
				if(j.hasOwnProperty("success")) {
					session = j["data"][sid]; 
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
		$('.visnav').css({'left':'0px'}); 
		$('.hidemenuoption').css({'visibility':'hidden'}); 
	} else { 
		$('.visnav').css({'left':'-270px'}); 
		$('.hidemenuoption').css({'visibility':'visible','left':'0px'}).html('&raquo;'); 
	}

	// $('.activity').css({
	// 	'width':(window.innerWidth-290)+'px' 
	// }); 
} 

function load_menu() {
	$.ajax({ 
		type: 'GET', 
		url: APPURL, 
		data: { 
			action: 'options' 
		}, 
		async: false, 
		success: function(data) {
			// console.log(data); 
			var j = $.parseJSON(data); 
			var sel = '<div class="coursecodes">'; 
			sel += '<select name="classcodeselect" class="input" id="classcodeselect">'; 
			sel += '<option selected="selected" disabled="disabled">Choose Class Code</option>'; 
			$('.subnav .codes').append(''); 
			for(var k in j["data"]) {
				var entry = j["data"][k]; 
				// console.log(entry["name"]); 
				sel += '<option value="'+entry["ccid"]+'">'+entry["name"]+'</option>'; 
			}
			sel += '</select><br><br>'; 
			sel += '<a href="#" class="button curtainOpen" id="sharecode" data=""> Share </a> '; 
			sel += '<a href="#" class="button curtainOpen" id="delcode" data=""> Delete </a> '; 
			sel += '<a href="#" class="button curtainOpen" id="addcode"> Add </a> '; 
			sel += '</div> '; 
			$('.subnav .codes').append(sel); 
		}
	}); 

	$('body').on('change','.input#classcodeselect',function() {
		var v = $(this).val(); 
		$('.button#delcode').attr('data','c'+v); 
		load_menu_options(v); 
	}); 
}

function load_menu_options(ccid) {
	console.log("getting "+ccid); 
	$.ajax({
		type: 'GET', 
		// url: 'http://environtracker.benova.net/_api/requests.php', 
		url: APPURL, 
		data: {
			action:'sessions', 
			ccid:ccid 
		}, 
		async:false, 
		success: function(data) {
			console.log(data); 
			var j = $.parseJSON(data); 
			$('.subnav .menuoptions').html('');
			if(j.hasOwnProperty("success")) {
				for(var i in j["data"]) {
					console.log(x); 
					var x = j["data"][i]; 
					$('.subnav .menuoptions').append('<li id="u'+x["id"]+'">'+x["user"]+'</li>');
				}
				// for(var i=0; i<j["data"].length; i++) {
				// 	var val = j["data"][i]; 
				// 	// $('.subnav .menuoptions').append('<li id="u'+val+'">'+val+'</li>'); 
				// 	console.log(j["data"][i]); 
				// }
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
			$('.visnav').animate({left:'0px'}, 500, function() {}); 
		} else {
			$('.hidemenuoption').animate({left:'0px'}, 500, function() {}).html('&raquo;'); 
			$('.visnav').animate({left:'-270px'}, 500, function() {}); 
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