var app = {}; 

$(document).ready(function() {
	// setDefaultBehaviour(); 
	setRoutes(); 
}); 

// function setDefaultBehaviour() {
// 	$('input').on('keydown', function(e) { 
// 		console.log('keydown'); 
// 		if(e.which==13)
// 			console.log($(this).next('input["button"]').attr('id')); 
// 	}); 
// }

function setRoutes() {
	app.routes = {}; 

	app.Router = Backbone.Router.extend({
		routes: {
			'': 'login', 
			'login': 'login', 
			'dashboard': 'dashboard'
		}, 
		login: function() { 
			app.route('content', 'client/modules/dashboard/login.html'); 
		}, 
		dashboard: function() {
			app.route('content', 'client/modules/dashboard/dashboard.html'); 
		}
	}); 

	app.clearRoutes = function(container) {
		if(container===undefined||container==null) 
			for(var r in app.routes) 
				app.routes[r].remove(); 

		else 
			if(container in app.routes) 
				app.routes[container].remove(); 
	}; 

	app.route = function(container, url) {
		app.clearRoutes(container); 
		$(container).html(''); 
		app.routes[container] = inject(url, container); 
	}; 

	app.routre = new app.Router(); 
	Backbone.history.start(); 
}