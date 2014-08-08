// =======================
// 	Visualization classes 
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

	this.clear = function() {
		this.hits = 0; 
		this.added = 0; 
	}
}


// =======================
// 	GameMap functions 
// =======================

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

function clear_grid_blocks() {
	d3.range(GRID.length).map(function(i) {
		GRID[i].clear(); 
	}); 
}
