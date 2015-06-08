(function(inject) {
    "use strict";

    inject = function(file, container) {

        var fileType;

        function getContainer() {
            var injectContainer = {
                'js': 'head', 
                'css': 'head', 
                'html': 'body', 
                'php': 'body' 
            }; 

            if(container===undefined) 
                return $(injectContainer[fileType]); 
            return $(container); 
        }

        function getFileType(file) {

            var fileType;

            fileType = file.split('.');
            fileType = fileType[fileType.length - 1]; 

            return fileType;

        }

        function injectFile(file, fileType) {

            var injectFile = {
                'js': injectJs,
                'css': injectCss,
                'html': injectHtml, 
                'php': injectHtml
            }

            return injectFile[fileType](file);
        }

        function injectJs(file) {

            var script = document.createElement('script');
            script.src = file;

            // Inject script into to document 
            container = getContainer(); 
            container.append(script); 
            return script; 

        }


        function injectCss(file) {

            // Create stylesheet link
            var link = document.createElement('link');

            // Add attributes
            link.href = file;
            link.rel = 'stylesheet'; 
            link.type = 'text/css';

            // Inject script into to document 
            container = getContainer(); 
            container.append(link); 
            return link; 
        }


        function injectHtml(file) {
            var idName = file, 
                removals = [".","/","\\"], 
                fileERror = false, 
                div = $(document.createElement('div')); 

            for(var r in removals) 
                while(idName.indexOf(removals[r])>=0) 
                    idName = idName.replace(removals[r],""); 

            container = getContainer(); 

            div.attr('class','template'); 
            div.attr('id',idName); 
            div.load(file, 
                {limit: 25}, 
                function(responseText, textStatus, req) {
                    if(textStatus=="error") {
                        console.log("error loading "+file); 
                        return "Error loading "+file+": directory doesn't exist."; 
                    }
                }
            ); 

            container.append(div); 

            return $(div); 
        }

        function run() {

            var fileType = getFileType(file);
            return injectFile(file, fileType);

        }

        return run();

    }

    if (typeof exports === 'object') {
        // CommonJS support
        module.exports = inject;
    } else if (typeof define === 'function' && define.amd) {
        // support AMD
        define(function() {
		return inject;
	    });
    } else {
        // support browser
        window.inject = inject;
    }

})({});