// ========================= 
// 	Visuzlization constants 
// ========================= 

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
	COLORS_MAIN_LIGHT 	= "#68dadc", 
	COLORS_MAIN_DARK 	= "#128483";
var CENTROID_COLOR 	= ["#ebceff",
                	   "#ffcece",
	                   "#ffebce",
	                   "#f9ffce",
	                   "#d5ffce",
	                   "#d3d3d3"]; 

var coords = {"NA":[100,110], 
			  "SA":[160,190], 
			  "EU":[250,100],
			  "AF":[280,160],
			  "CA":[380,80],
			  "EA":[410,130]}

// visualization 
var selected_visualization = 0; 
var selected_region = 'w'; 
var session = {}; 
var bases = {}; 



// ========== 
// 	Formulas  
// ========== 

function distance_formula(x1,y1,x2,y2) {
	return Math.sqrt(Math.pow(x1-x2,2) + Math.pow(y1-y2,2));
}

function slope_formula(x1,y1,x2,y2) {
	return (y2-y1)/(x2-x1); 
}

function sort_object(obj) {
    var arr = [];
    for (var prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            arr.push({
                'key': prop,
                'value': obj[prop]
            });
        }
    }
    arr.sort(function(a, b) { return a.value - b.value; });
    return arr; 
}




// =================
// 	Color Functions 
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


function get_map_color(ratio) {
	if(ratio<0.1 || isNaN(ratio)) return COLORS_WHITE;
    else if(ratio<0.2) return get_color_in_between(ratio,COLORS_WHITE,COLORS_CYAN);
    else if(ratio<0.5) return get_color_in_between(ratio,COLORS_CYAN,COLORS_GREEN);
    else if(ratio<0.8) return COLORS_YELLOW;
	else if(ratio<0.9) return get_color_in_between(ratio,COLORS_GREEN,COLORS_YELLOW);
	else if(ratio<1) return get_color_in_between(ratio,COLORS_YELLOW,COLORS_RED);
    else return COLORS_RED;
}