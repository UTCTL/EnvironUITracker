// ======================= 
// 	Visuzlization globals 
// ======================= 

// structure 
var SVG, ENVIRON, ECONOMY, SELECTED; 
var GRID = [], 
	CLICKS = [], 
	centroids = [], 
	TOLERANCE = 0.36;

// dimentsion s
var w = 1200,	// SVG width  
	h = 700, 	// SVG height
	dx = 120, 	// width of blocks on GRID
	dy = 70, 	// height of blocks on GRID
	rx = w/dx, 	// how many blocks horizontally on GRID
	ry = h/dy, 	// how many blocks vertically on GRID
	gw = 516, 	// score graph width 
	gh = 199; 	// score graph height 

// colors 
var COLORS_WHITE 	= "#ffffff", 
	COLORS_CYAN 	= "#00ffff",
	COLORS_GREEN 	= "#00ff00",
	COLORS_VERDE 	= "#00aa00", 
	COLORS_YELLOW 	= "#ffff00",
	COLORS_AMBER 	= "#aaaa00", 
	COLORS_RED 		= "#ff0000",
	COLORS_PURPLE 	= "#902c8e", 
	COLORS_MAIN_LIGHT 	= "#98e1fd", 
	COLORS_MAIN_DARK 	= "#008ec5";
var CENTROID_COLOR 	= ["#ebceff",
                	   "#ffcece",
	                   "#ffebce",
	                   "#f9ffce",
	                   "#d5ffce",
	                   "#d3d3d3"]; 

// visualization 
var selected_visualization = 0; 


// =======================
// 	Visuzlization classes 
// =======================

// click 
function Click() {
	this.instantiate = function(svg,x,y) {
		this.x = x; 
		this.y = y; 
		this.circle = svg.append('circle') 
						 .attr('cx',x) 
						 .attr('cy',y) 
						 .attr('r',2) 
						 .attr('fill',COLORS_RED); 
	}; 
}

// block (grid) 
function Block() {
	this.instantiate = function(id,svg,x,y) {
		var rand = Math.floor(Math.random()*3); 
		this.id = id; 
		this.x = x; 
		this.y = y; 
		this.hits = 0; 
		this.added = 0; 
		this.rect = svg.append('rect')
					   .attr('x',x) 
					   .attr('y',y) 
					   .attr('width',rx) 
					   .attr('height',ry) 
					   .style('fill',COLORS_WHITE); 
	}; 

	this.hit = function() {
		this.hits++; 
	}

	this.increment = function(k) {
		this.added+=k; 
	}
}

// centroid 
function Centroid() {
	this.instantiate = function(id,svg) {
		this.id = id; 
		this.x = Math.floor(Math.random()*w); 
		this.y = Math.floor(Math.random()*h); 
		this.distances = {}; 
		this.attributions = []; 
	}; 

	this.dist = function(b) {
		this.distances[b.id] = distance_formula(this.x,this.y,b.x,b.y); 
	}; 

	this.attribute = function(b) {
		this.attributions.push(b); 
	}; 

	this.repos = function() {

		if(this.attributions.length==0) {
			this.x = 0; 
			this.y = 0; 
		} else {
			var px = 0, 
				py = 0; 
			var attrs = this.attributions; 
			var color_index = this.id; 
			d3.range(this.attributions.length).map(function(i) {
				var a = attrs[i]; 
				px += a.x; 
				py += a.y; 
				a.rect.transition().duration(100) 
					  .style('fill','#f00'); 
			}); 
			
			this.x = Math.floor(px/this.attributions.length); 
			this.y = Math.floor(py/this.attributions.length); 
		}
	}
}


// =======================
// 	Main Driver 
// =======================

$(document).ready(function() {
	resize_user_activity_section(); 
	load_menu(); 
	hide_show_menu(); 
	premake_svgs(); 

	// choose item from menu 
	$('.usermenu li').on('click',function() { 
		$('.usermenu li#'+SELECTED).removeClass('selected'); 

		var id = $(this).attr('id'); 
		SELECTED = id; 
		$('.usermenu li#'+SELECTED).addClass('selected'); 

		id = id.substring(1,id.length); 
		// console.log(id); 
		$.ajax({
			type: 'GET', 
			// url: 'testloader.php', 
			url: '_api/requests.php', 
			data: {
				action:'pull', 
				id:id 
			}, 
			async: false, 
			success: function(data) {
				var j = $.parseJSON(data); 
				if(j.hasOwnProperty("success")) {
					display_data(j["data"][id]); 
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
		$('.usermenu').css({'left':'0px'}); 
		$('.menuoption').css({'visibility':'hidden'}); 
	} else { 
		$('.usermenu').css({'left':'-250px'}); 
		$('.menuoption').css({'visibility':'visible','left':'0px'}).html('&raquo;'); 
	}

	$('.useractivity').css({
		'width':(window.innerWidth-290)+'px' 
	}); 
} 

function load_menu() {
	$.ajax({
		type: 'GET', 
		url: '_api/requests.php', 
		data: {
			action:'options'
		}, 
		async:false, 
		success: function(data) {
			var j = $.parseJSON(data); 
			if(j.hasOwnProperty("success")) {
				for(var i=0; i<j["data"].length; i++) {
					var val = j["data"][i]; 
					$('.usermenu').append('<li id="u'+val+'">'+val+'</li>'); 
				}
			}
		}
	})
}

// hide/show menu depending on screen size 
function hide_show_menu() {
	$('.menuoption').on('click',function() {
		var x = $(this).offset().left; 
		if(x<10) {
			$('.menuoption').animate({left:'250px'}, 500, function() {}).html('&laquo;'); 
			$('.usermenu').animate({left:'0px'}, 500, function() {}); 
		} else {
			$('.menuoption').animate({left:'0px'}, 500, function() {}).html('&raquo;'); 
			$('.usermenu').animate({left:'-250px'}, 500, function() {}); 
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
	SVG = d3.select('.game').append('svg')
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

// increment added value of adjacent block 
function increment_adjacent(i,v) {
	if(v<0.1) return; 
	var j = 0; 

	for(var k=0; k<3; k++) {
		j = (k==0) ? i-dx-2 : (k==1) ? i-2 : i+dx-2; 

		for(var l=0; l<3; l++) {
			j++; 
			if(j==l) continue; 
			if(j<GRID.length && j>=0) {
				GRID[j].increment(v); 
				increment_adjacent(j,v/2); 
			}
		}
	}
}

// hit grid block
function hit_grid_block(x,y) {
	var newx = Math.floor(x/rx); 
	var newy = Math.floor(y/ry); 
	var i = (newy*dx)+newx; 
	GRID[i].hit(); 
	increment_adjacent(i,TOLERANCE); 
}

// show data for camera movement 
function move_camera_data(type,value,total) {
	var p = value/total; 
	var v = Math.round(p*100); 
	$('.movement#'+type+' .label').html(v+"%"); 
	if(v==0) {
		$('.movement#'+type+' .bargraph').addClass('zero'); 
		$('.movement#'+type+' .bargraph .bar .model').animate({
			'width':'35px' 
		},700,function() {}).css({
			'background-color':'#bbb'
		}); 
	} else { 
		// console.log(type+" "+value+" "+get_color_in_between(p,COLORS_MAIN_LIGHT,COLORS_MAIN_DARK) );
		$('.movement#'+type+' .bargraph').removeClass('zero'); 
		$('.movement#'+type+' .bargraph .bar .model').animate({
			'width':Math.round(35+(415*p))+'px' 
		},700,function() {}).css({
			'background-color':get_color_in_between(p,COLORS_MAIN_LIGHT,COLORS_MAIN_DARK) 
		}); 
	}
}

function distance_formula(x1,y1,x2,y2) {
	return Math.sqrt(Math.pow(x1-x2,2) + Math.pow(y1-y2,2));
}

function slope_formula(x1,y1,x2,y2) {
	return (y2-y1)/(x2-x1); 
}

// display gather data for a particular session 
function display_data(session) {
	// console.log(session); 

	// SESSION id
	$('#sessionid').html(session["id"]); 

	// SESSION user meta info 
	var meta = ""; 
	meta += session["meta"]["age"]+"-year-old "; 
	meta += session["meta"]["race"]+" "; 
	meta += (session["meta"]["gamer"]) ? "gamer " : "non-gamer "; 
	meta += (session["meta"]["gender"]=="m") ? "male " : "female "; 

	$('.info .meta').html(meta); 

	// SESSION game details 
	var win = (session["details"]["status"]==0) ? '<span class="chosen">won</span>' : 'won'; 
	var los = (session["details"]["status"]==1) ? '<span class="chosen">lost</span>' : 'lost'; ; 
	var qui = (session["details"]["status"]==2) ? '<span class="chosen">quit</span>' : 'quit'; ; 

	$('.resources #funds .label').html(session["details"]["spent_funds"]); 
	$('.resources #pc .label').html(session["details"]["spent_polcap"]); 
	$('.resources #level .label').html(session["details"]["level"]); 
	$('.resources #timer .label').html(session["details"]["playtime"]); 
	$('.resources #status .label').html(win+" | "+los+" | "+qui); 
	$('.resources #completed .label').html(session["details"]["completed"]+"%"); 

	// SESSION screen usage 

	// ========================
	// 	Screen Visualization 
	// ========================
	SVG.selectAll('circle').remove(); 
	var data = session["screen_usage"]["clicks"]; 
	for(var i=0; i<data.length; i++) {
		var c = new Click(); 
		var x = data[i][0]; 
		var y = 700-data[i][1]; 
		c.instantiate(SVG,x,y); 
		CLICKS.push(c); 
		hit_grid_block(x,y); 
	}

	// SESSION camera movement 
	data = session["camera_movement"]; 
	var tags = ["drag","arrows","keys"]; 
	var total = 0; 
	for(var i=0; i<tags.length; i++) 
		total += data[tags[i]]; 
	for(var i=0; i<tags.length; i++) 
	 	move_camera_data(tags[i],data[tags[i]],total); 

	// SESSION panel time 
	total = session["panel_time"]["open"]+session["panel_time"]["closed"]; 
	total = Math.round(session["panel_time"]["open"]*100/total); 

	if(total>=50) {
		$('.panel#on .label').removeClass("negative").addClass("positive"); 
		$('.panel#off .label').removeClass("positive").addClass("negative"); 
	} else {
		$('.panel#on .label').removeClass("positive").addClass("negative"); 
		$('.panel#off .label').removeClass("negative").addClass("positive"); 
	} 

	$('.panel#on .label').html(total+"%"); 
	$('.panel#off .label').html((100-total)+"%"); 

	// SESSION graph score plots 
	graph_scores(session,"environ"); 
	graph_scores(session,"economy"); 

	// SESSION node rate 
	var k = 0, n = 0; 
	var types = ["spawn","unlock","active","responsive"]; 
	var nodes = ["bases","upgrades","events","disasters"]; 
	var v; 

	for(var i=0; i<types.length; i++) {
		n=0; 
		for(var j=0; j<nodes.length; j++) {
			v = session["node_rate"][types[i]][nodes[j]]; 
			$('#node'+k+""+n).html((v==0) ? '-' : v.toFixed(2)); 
			n++; 
		}
		k++; 
	} 

	// SESSION Region data particular 
	types = ["NA","SA","EU","AF","CA","EA"]; 
	nodes = ["order","environ","economy","focus"]; 
	k = 0; 

	for(var i=0; i<types.length; i++) {
		n=0; 
		for(var j=0; j<nodes.length; j++) {
			v = session["region_data"]["particular"][types[i]][nodes[j]]; 

			switch(j) {
				case 0: 
					$('#region'+k+""+n).html(v); 
					break; 
				case 1: 
				case 2: 
					$('#region'+k+""+n).html(v.toFixed(1)); 
					if(v>0) $('#region'+k+""+n).removeClass('negative').addClass('positive'); 
					else $('#region'+k+""+n).removeClass('positive').addClass('negative'); 
					break; 
				default: 
					$('#region'+k+""+n).html(v+"%"); 
			}
			n++; 
		}
		k++; 
	} 


	// SESSION region data general 
	data = session["region_data"]["general"]["navigation"]; 
	total = data["minimap"]+data["keys"]; 
	v = (data["minimap"]/total); 
	$('#funds .label').html(session["region_data"]["general"]["unlock_rate"]); 

	$('.navigation .bargraph .bar .label#mini').html("Minimap "+(Math.floor(v*100))+"%"); 
	$('.navigation .bargraph .bar #minimap').animate({
		'width':v*430+'px'
	},700,function() {}); 
	$('.navigation .bargraph .bar .label#regk').html("Keys "+(Math.floor((1-v)*100))+"%"); 
	$('.navigation .bargraph .bar #regkeys').animate({
		'width':(430-(v*430))+'px', 
		'left':(v*430)+20
	},700,function() {}); 


}


// =================
// 	COLOR Functions 
// =================
function get_color_in_between(ratio,color1,color2) {
    color1 = hex_to_decimal(color1);
    color2 = hex_to_decimal(color2);
    var color3 = [];

	for(var i=0; i<color1.length; i++) {
		if(color1[i]==color2[i]) color3[i] = color1[i];
        else {
            var d = Math.abs(color1[i]-color2[i]);
            color3[i] = Math.floor(Math.min(color1[i],color2[i])+(d*ratio));
    	}
    } 

	color3 = decimal_to_hex(color3);
	return color3;

}

function hex_to_decimal(color) {
    var num = 0,
        k = 0,
        ch = '',
        temp = [],
        hex = [],
        prov = '';

    for(var i=1; i<color.length; i++) {
        prov += color[i];
		if(i%2==0) {
			hex[k] = prov;
			k++;
			prov = "";
        }
    }

    for(var j=0; j<hex.length; j++) {
        num = 0;
        for(var i=hex[j].length-1; i>=0; i--) {
			ch = hex[j][hex[j].length-(i+1)];
			k = Math.floor((isNaN(parseInt(ch))) ? ch.charCodeAt(0)-87 : parseInt(ch));
			num += k*(Math.pow(16, i));
		}
		temp[j] = num;
    }

	return temp;
}

function decimal_to_hex(color) {
    var temp = [],
		hex = "",
        result = 0,
        remainder = 0;

    for(var i=0; i<color.length; i++) {
        hex = "";
        result = color[i];
        if(result<10) temp[i] = "0"+result;
        else if(result>=255) temp[i] = "ff";
		else {
            while(result>0) {
				remainder = Math.floor(result%16);
				result = Math.floor(result/16);
                hex = ((remainder<10) ? ""+remainder : String.fromCharCode(87+remainder))+hex;
			}
        	temp[i] = hex;
        }

        if(temp[i].length<2) temp[i] = "0"+temp[i]; 
    }

    // console.log(temp); 
    return "#"+temp.join("");
}

function graph_scores(session,type) {
	var canvas = (type=="environ") ? ENVIRON : ECONOMY; 
	var count = session["scores"][type].length; 
	var delta = gw/(count-1); 
	var total = 0; 
	var zero = gh/2; 
	var prev = 0; 
	var slopes = []; 
	d3.selectAll('.score#'+type+' .graphplot line').remove(); 
	d3.range(count).map(function(i) { 
		var val = session["scores"][type][i]; 
		if(i==0) {
			prev = val; 
			return; 
		}

		var color; 
		if(val>25) color = COLORS_VERDE; 
		else if(val>0) color = get_color_in_between(1-(val/25), COLORS_AMBER, COLORS_VERDE); 
		else if(val>-25) color = get_color_in_between(1+(val/25),COLORS_AMBER,COLORS_RED); 
		else color = COLORS_RED; 

		total+=val; 

		var x1 = (i-1)*delta, 
			y1 = gh-(zero+((prev/100)*zero)), 
			x2 = i*delta, 
			y2 = gh-(zero+((val/100)*zero)); 

		slopes[i] = slope_formula(i-1,prev,i,val); 
		canvas.append('line') 
			  .attr('x1',x1)  
			  .attr('y1',zero) 
			  .attr('x2',x2)  
			  .attr('y2',zero) 
			  .attr('stroke','#fff') 
			  .attr('stroke-width','2px')
			  .transition().duration(1000)
			  .attr('x1',x1)  
			  .attr('y1',y1) 
			  .attr('x2',x2)  
			  .attr('y2',y2)
			  .attr('stroke',color) 
			  .attr('stroke-width','5px'); 

		prev = val; 

	}); 

	var avg = 0; 
	d3.range(slopes.length).map(function(i) { if(i==0) return; avg+=slopes[i]; }); 
	avg = avg/slopes.length; 
	$('#avg_'+type+'_delta').html(avg.toFixed(2)); 
	if(avg>0) $('.data#'+type+' .label#avg_'+type+'_delta').removeClass('negative').addClass('positive'); 
	else $('.data#'+type+' .label#avg_'+type+'_delta').removeClass('positive').addClass('negative'); 

	avg = total/count; 
	$('#avg_'+type+'_score').html(avg.toFixed(2)); 
	if(avg>0) $('.data#'+type+' .label#avg_'+type+'_score').removeClass('negative').addClass('positive'); 
	else $('.data#'+type+' .label#avg_'+type+'_score').removeClass('positive').addClass('negative'); 	
	canvas.append('line') 
		  .attr('x1',0) 
		  .attr('y1',zero) 
		  .attr('x2',gw) 
		  .attr('y2',zero) 
		  .attr('style','stroke:#73d0f4; stroke-width:1px; ')
		  .transition().duration(1000) 
		  .attr('x1',0) 
		  .attr('y1',zero-avg) 
		  .attr('x2',gw) 
		  .attr('y2',zero-avg); 
}

// =================
// 	DRAWING SCREEN  
// =================
function draw_points() {
	selected_visualization = 0; 
    d3.range(GRID.length).map(function(i) {
       	GRID[i].rect.style('fill',COLORS_WHITE);
    });
}

function draw_heatmap() {
	selected_visualization = 1; 
    var max = 0;
    var total = 0;
    d3.range(GRID.length).map(function(i) {
        if(GRID[i].hits>max) max = GRID[i].hits;
        total += GRID[i].hits;
    });

    d3.range(GRID.length).map(function(i) {
        GRID[i].rect.style('fill',get_map_color((GRID[i].hits+GRID[i].added)/max));
    });
}

function draw_cluster() {
	return; 

}

function get_map_color(ratio) {
	if(ratio<0.1 || isNaN(ratio)) return COLORS_WHITE;
    else if(ratio<0.2) return get_color_in_between(ratio,COLORS_WHITE,COLORS_CYAN);
    else if(ratio<0.5) return get_color_in_between(ratio,COLORS_CYAN,COLORS_GREEN);
    else if(ratio<0.8) return COLORS_YELLOW;
	else if(ratio<0.9) return get_color_in_between(ratio,COLORS_GREEN,COLORS_YELLOW);
	else if(ratio<1) return get_color_in_between(ratio,COLORS_YELLOW,COLORS_RED);
    else return COLORS_RED;
}