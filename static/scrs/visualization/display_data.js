function hide_sections() {
	$('.activity section').hide(); 
	$('body').on('click','.visnav li',function() {
		$('.activity section').fadeIn(1000); 
	}); 
}

// display gather data for a particular session 
function display_data() {
	clear_grid_blocks();
	// console.log("displaying data"); 

	$('.regionbuttons .button#'+selected_region).click(); 

	// SESSION id
	$('#sessionid').html(session["meta"]["session"]); 

	// SESSION user meta info 
	$('.meta #age b').html(session["meta"]["age"]); 
	$('.meta #gender b').html(session["meta"]["gender"]); 
	$('.meta #ethnic b').html(session["meta"]["ethnic"]); 
	$('.meta #gamer_no b').html(session["meta"]["gamer"]); 
	$('.meta #ipaddr b').html(session["meta"]["ipaddr"]); 
	$.ajax({
		type:'GET', 
		url:'http://freegeoip.net/json/'+session["meta"]["ipaddr"], 
		success:function(data) { 
			var iploc = data["city"]+", "+((data["country_code"]=="US") ? data["region_code"] : data["country_code"]); 
			$('.meta #iploc b').html(iploc); 
		}, 
		error: function(data) {
			// console.log(data);
		}
	}); 

	// SESSION game details 
	var win = (session["details"]["status"]==0) ? '<span class="chosen">won</span>' : 'won'; 
	var los = (session["details"]["status"]==1) ? '<span class="chosen">lost</span>' : 'lost'; ; 
	var qui = (session["details"]["status"]==2) ? '<span class="chosen">quit</span>' : 'quit'; ; 

	$('.meta #funds b').html(session["details"]["spent_funds"]); 
	$('.meta #pc b').html(session["details"]["spent_polcap"]); 
	$('.meta #level b').html(session["details"]["level"]); 
	$('.meta #time b').html(get_time(session["details"]["playtime"])); 
	$('.meta #status b').html(win+" | "+los+" | "+qui); 
	$('.meta #completed b').html(session["details"]["completed"]+"%"); 
	 

	// SESSION screen usage 

	// ========================
	// 	Screen Visualization 
	// ========================
	SVG.selectAll('circle').remove(); 
	var data = session["input"]["screen_usage"]["clicks"]; 
	for(var i=0; i<data.length; i++) {
		var c = new Click(); 
		var x = data[i][0]; 
		var y = 700-data[i][1]; 
		c.instantiate(SVG,x,y); 
		CLICKS.push(c); 
		hit_grid_block(x,y); 
	}

	// SESSION camera movement 
	data = session["input"]["camera_movement"]; 
	var tags = ["drag","arrows","keys"]; 
	var total = 0; 
	for(var i=0; i<tags.length; i++) 
		total += data[tags[i]]; 
	for(var i=0; i<tags.length; i++) 
	 	move_camera_data(tags[i],data[tags[i]],total); 

	// SESSION panel time 
	total = session["input"]["panel_time"]["open"]+session["input"]["panel_time"]["closed"]; 
	total = Math.round(session["input"]["panel_time"]["open"]*100/total); 

	if(total>=50) {
		$('.panel#on .label').removeClass("negative").addClass("positive"); 
		$('.panel#off .label').removeClass("positive").addClass("negative"); 
	} else {
		$('.panel#on .label').removeClass("positive").addClass("negative"); 
		$('.panel#off .label').removeClass("negative").addClass("positive"); 
	} 

	console.log("total for panel: "+total); 

	$('.panel#on .label').html(total+"%"); 
	$('.panel#off .label').html((100-total)+"%"); 
	// $('.panel_clicks').html("Panel has been opened x times and closed y times."); 


	var open = session["input"]["panel_time"]["click_open"]; 
	var close = session["input"]["panel_time"]["click_close"]; 
	total = open+close; 
	panel_clicks_data("open",open,total); 
	panel_clicks_data("close",close,total); 
	$('.panel_clicks#open .label').html(open); 
	$('.panel_clicks#close .label').html(close); 


	// SESSION navigation 
	// console.log(session); 
	var minimap = session["input"]["navigation"]["minimap"], 
		keys = session["input"]["navigation"]["keys"], 
		ratio = 0; 
	total = minimap+keys; 
	ratio = minimap/total; 

	// minimap 
	$('.navigation #minimap').animate({ 'width':(430*ratio)+'px' }); 
	$('.navigation .label#mini').html('Minimap '+(Math.round(ratio*100))+'%'); 
	
	// region keys 1-6 
	$('.navigation #regkeys').animate({ 
		'width':(430*(1-ratio))+'px', 
		'left':((430*ratio)+20)+'px' 
	}); 
	$('.navigation .label#regk').html('Keys '+(Math.round((1-ratio)*100))+'%'); 

	// SESSION graph score plots 

	graph_scores("environ"); 
	graph_scores("economy"); 

	// SESSION region info 
	var region_data = []; 
	var i = 0; 
	for (var inits in session["regions"]) {
		if(inits=="W") continue; 
		var d = {"region":inits, "value":get_region_focus(inits), 'coords':coords[inits]}; 
		region_data[i] = d; 
		i++; 
	}

	focus_regions(region_data); 

	// SESSION bases info 
	bases = {}; 
	get_bases_data(); 
	focus_bases(); 


	// SESSION distribution 
	show_region_data(selected_region); 
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





// show data panel open/close clicks
function panel_clicks_data(type,value,total) { 
	var p = value/total; 
	var v = Math.round(p*100); 
	$('.panel_clicks#'+type+' .label').html(v+"%"); 
	if(v==0) {
		$('.panel_clicks#'+type+' .bargraph').addClass('zero'); 
		$('.panel_clicks#'+type+' .bargraph .bar .model').animate({
			'width':'35px' 
		},700,function() {}).css({
			'background-color':'#bbb'
		}); 
	} else { 
		// console.log(type+" "+value+" "+get_color_in_between(p,COLORS_MAIN_LIGHT,COLORS_MAIN_DARK) );
		$('.panel_clicks#'+type+' .bargraph').removeClass('zero'); 
		$('.panel_clicks#'+type+' .bargraph .bar .model').animate({
			'width':Math.round(35+(415*p))+'px' 
		},700,function() {}).css({
			'background-color':get_color_in_between(p,COLORS_MAIN_LIGHT,COLORS_MAIN_DARK) 
		}); 
	}
}



function graph_scores(type) {
	var canvas = (type=="environ") ? ENVIRON : ECONOMY; 
	var initials = selected_region.toUpperCase(); 
	var count = session["regions"][initials]["scores"][type].length; 
	var delta = gw/(count-1); 
	var total = 0; 
	var zero = gh/2; 
	var prev = 0; 
	var slopes = []; 
	d3.selectAll('.score#'+type+' .graphplot line').remove(); 
	// if(initials=="W") return; 

	$('.rate h2 .label').html(session["regions"][initials]["name"]); 

	d3.range(count).map(function(i) { 
		var val = session["regions"][initials]["scores"][type][i]; 
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



function bake_pie(data) {
	if(data==null) return;
	d3.select(".piechart svg").remove("g"); 

	var w = 300,                        //width
    	h = 300,                            //height
    	r = 150,                            //radius
    	color = ['#f5f5f5','#ebebeb','#e5e5e5','#dbdbdb','#d5d5d5']; //d3.scale.category20c();     //builtin range of colors

    
    var vis = d3.select(".piechart")
        .append("svg:svg")              //create the SVG element inside the <body>
        .data([data])                   //associate our data with the document
        .attr("width", w)           //set the width and height of our visualization (these will be attributes of the <svg> tag
        .attr("height", h)
        .append("svg:g")                //make a group to hold our pie chart 
        .attr("transform", "translate(" + r + "," + r + ")")    //move the center of the pie chart from 0, 0 to radius, radius

    var arc = d3.svg.arc()              //this will create <path> elements for us using arc data 
        .outerRadius(r);

    var pie = d3.layout.pie()           //this will create arc data for us given a list of values
        .value(function(d) { return d.value; });    //we must tell it out to access the value of each element in our data array

    var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
        .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
        .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
        .append("svg:g")                //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
        .attr("class", "slice");    //allow us to style things in the slices (like text)

    arcs.append("svg:path")
        .attr("fill", function(d, i) { return get_color_in_between(i/data.length,'#e0e0e0','#fafafa'); /*return color[i];*/ } ) //set the color for each slice to be chosen from the color function defined above 
        .attr("d", arc)
        .attr("class","pie_slice");                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function

    arcs.append("svg:text")                                     //add a label to each slice
        .attr("transform", function(d) {                    //set the label's origin to the center of the arc
            //we have to make sure to set these before calling arc.centroid
            d.innerRadius = r/2;
            d.outerRadius = r;
            return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
        })
        .attr("text-anchor", "middle")                          //center the text on it's origin
        .attr("class","pie_text")
        .text(function(d, i) { return data[i].label/*+" "+Math.round((data[i].value/180)*100)+"%"*/; });        //get the label from our original data array
}

function focus_regions(data) {
	if(data==null) return;
	d3.select(".regionfocus .mapsmall svg").remove("circle"); 

	var vis = d3.select(".regionfocus .mapsmall") 
				.append("svg") 
				.attr("w",530) 
				.attr("h",280); 

	var relationships = {}; 
	var total = 0; 
	var max = {"index":0,"value":0}; 
	d3.range(data.length).map(function(i) {
		total += data[i]["value"]; 
		relationships[data[i]["region"]] = {"focus":data[i]["value"], "order":session["regions"][data[i]["region"]]["order"]}; 
		if(data[i]["value"]>max["value"]) {
			max["index"] = i; 
			max["value"] = data[i]["value"]; 
		}
	})

	d3.range(data.length).map(function(i) {
		// console.log(data[i]["value"]+'/'+total); 
		var x = data[i]["coords"][0], 
			y = data[i]["coords"][1], 
			f = (total==0) ? 0 : data[i]["value"]/total, 
			m = f/(max["value"]/total), 
			r = (isNaN(f)) ? 11 : 11 + (f*70); 

		// console.log('11 + ('+f+'*70) = '+r); 

		vis.append('circle')
		   .attr('cx',x) 
		   .attr('cy',y) 
		   .attr('r',15)
		   .attr('fill',COLORS_MAIN_DARK) 
		   .transition().duration(1000) 
		   .attr('r',r) 
		   .attr('fill',get_color_in_between(m,COLORS_MAIN_DARK,COLORS_MAIN_LIGHT)); 
		vis.append('text')
	       .attr("dx", x) 
	       .attr("dy", y+6) 
           .attr("text-anchor", "middle")
	       .attr("fill","#fff")
	       .text(Math.round(f*100)+"%");
	}); 


	// get linear regression to establish correlation between 
	// region activation order and focus 
	var x = [1,2,3,4,5,6], 
		y = []; 

	for(var r in relationships) {
		for(var i=0; i<x.length; i++) {
			if(relationships[r]["order"]==x[i]) {
				y[i] = relationships[r]["focus"]; 
			}
		}
	}

	var linreg = -1*linear_regression_formula(x,y); 

	if(linreg>0.25) {
		$('.correlation').removeClass('neutral').removeClass('negative').addClass('positive')
						 .html('Forst this user... positive'); 
	} else if(linreg <-0.25) {
		$('.correlation').removeClass('positive').removeClass('neutral').addClass('negative')
						 .html('Forst this user... negative'); ; 
	} else {
		$('.correlation').removeClass('positive').removeClass('negative').addClass('neutral')
						 .html('Forst this user... neutral'); ; 
	}
}

function activate_region_buttons() {
	$('.regionbuttons .button').on('click',function() {
		$('.regionbuttons .button').removeClass('selected'); 
		$(this).addClass('selected'); 

		var id = $(this).attr('id'); 
		selected_region = id; 
		if(id=="w") {
			$('.regionfocus, .basefocus').fadeIn(); 
			$('.distribution').hide(); 
		} else { 
			$('.regionfocus, .basefocus').hide(); 
			$('.distribution').fadeIn(); 
			$('.distribution svg').attr('id',selected_region); 

			show_region_data(selected_region); 
		}

		graph_scores("environ"); 
		graph_scores("economy"); 
	}); 

	$('.staticdivider').hide(); 
	// var o = $('.divider').offset().top; 
	// var m = parseFloat($('.divider').css('margin-top').replace(/auto/,0)); 
	// var s = ($('.distribution:visible').length==1) ? $('.distribution').offset().top : 0; 

	// $(window).scroll(function() {
	// 	// var t = $('.divider').scrollTop(); 
	// 	var w = $(this).scrollTop()+100; 
	// 	var topn =  o - m;
		
	// 	// console.log(w+" >= "+topn); 
	// 	if(w >= topn) $('.staticdivider').show(); 
	// 	else $('.staticdivider').hide(); 

	// 	var k = w+100; 
	// 	if(k >= s-m) $(this).stop(); 
	// }); 
}

function get_time(time) {
	hours = Math.floor(time/3600); 
	minutes = Math.floor(time/60); 
	seconds = Math.floor(time-(minutes*60)); 

	return hours+"h "+minutes+"m "+seconds+"s "; 
}

function get_region_focus(init) {

	// return active/total; 
	var total = 0; 
	for(var i=0; i<session["regions"][init]["bases"].length; i++) {
		if(session["regions"][init]["bases"][i]["active"]) {
			var b = session["regions"][init]["bases"][i]; 
			for(var j=0; j<b["upgrades"].length; j++) {
				var u = b["upgrades"][j]; 
				if(u["active"])
					total++; 
			}
		}
	}

	return total; 
}

function get_bases_data() {
	for(var region in session["regions"]) {
		for(var b in session["regions"][region]["bases"]) {
			base = session["regions"][region]["bases"][b];
			if(base["active"]) {
				for(var u in base["upgrades"]) {
					var upgrade = base["upgrades"][u]; 
					if(upgrade["active"]) {
						if(base["name"] in bases) {
							bases[base["name"]]++; 
						} else {
							bases[base["name"]] = 1; 
						}
					}
				}
			}
		}
	}

	bases = sort_object_by_key(bases); 
}

function focus_bases() {
	data = []; 
	j = 0; 
	for(var i=bases.length-1; i>=0; i--) { 
		var n = bases[i]; 
		d = {"label":n.key, "value": n.value}; 
		data[j] = d; 
		j++; 
	}

    bake_pie(data); 
}

function show_region_data(id) {
	var region = session["regions"][id.toUpperCase()], 
		spent_funds = region["spent_funds"], 
		spent_polcap = region["spent_polcap"], 
		theta = 360/region["bases"].length; 
		dist = 250
		svg = ""; 

	$('#funds_small b').html(spent_funds); 
	$('#pc_small b').html(spent_polcap); 

	svg = d3.select('.distribution svg'); 
	svg.selectAll('circle').remove(); 
	d3.range(region["bases"].length).map(function(i) {
		var x = $('.distribution svg').width()/2 + dist*Math.cos(i*theta/180*Math.PI + Math.PI), 
			y = $('.distribution svg').height()/2 + dist*Math.sin(i*theta/180*Math.PI + Math.PI); 

		// console.log('('+x+','+y+')'); 

		var upgrades = region["bases"][i]["upgrades"]; 
		var uweight = 0; 
		d3.range(upgrades.length).map(function(j) {
			uweight += (upgrades[j]["active"]) ? 1 : 0; 
		}); 

		var ratio = (upgrades.length==0) ? 0 : uweight / upgrades.length; 

		svg.append('circle')
		   .attr('cx',x) 
		   .attr('cy',y) 
		   .attr('r',30+(ratio*20))
		   .attr('fill',get_color_in_between(ratio,'#006261','#79ebed')) 
		   .attr('id','base'+i); 
	}); 

    $('.distribution svg circle').on('click',function() {
    	var baseid = parseInt($(this).attr('id').substring(4)); 
    	load_base_info(region,region["bases"][baseid]); 
    	$('.distribution svg circle').css({'stroke-width':'0px'}); 
    	$(this).css({'stroke':'#ffde16','stroke-width':'5px'}); 
    }); 

    $('.distribution svg circle').first().click(); 
}

function load_base_info(region,base) {
	$('.distribution .dgui h2').html(region["name"]); 
	$('.distribution .dgui h3').html(base["name"]); 

	$('.distribution .dgui .upgrades').html(''); 
	for(var i in base["upgrades"]) {
		var u = base["upgrades"][i]; 
		$('.distribution .dgui .upgrades').append('<li class="'+(((u["active"]) ? 'active' : 'inactive'))+'">'+u["name"]+'</li>'); 
	}
}