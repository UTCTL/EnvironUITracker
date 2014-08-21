<? 
?>

<!DOCTYPE html>
<html><head>
 <script src="../static/scrs/jquery.min.js"></script>
 <script type="text/javascript"> 
 $(document).ready(function() {
	$('body').on('click','.button#push',function() {
		$.ajax({
			type:'GET', 
			url:'../controllers/api.php', 
			data: {
				action:'test' 
			}, 
			success: function(data) {
				$('.results').html(data); 
			}, 
			error: function(data) {
				$('.results').append('<div class="error">error</div>'); 
			}
		}); 
	}).on('click','.button#pull',function() {
		$.ajax({
			type:'GET', 
			url:'../controllers/api.php', 
			data: {
				action:'pull', 
				id:'bdf3d3bf4a519c2cb2c34cbe7fb09cb9'  
			}, 
			success: function(data) {
				console.log(data); 
				console.log($.parseJSON(data)); 
			}, 
			error: function(data) {
				console.log(data); 
			}
		}); 
	}); 
 }); 
 </script> 

 <style type="text/css">
 * { padding:10px; margin:0px; } 
 body { font-family:helvetica; }
 body * { display:block; }
 .button { background-color:#06c; 
	color:#fff; 
	cursor:pointer; 
	margin-bottom:10px; } 
 .button:hover { background-color:#09f; 
	color:#fff; } 
 #spacing, #value, #saving { color:#ddd; display:inline-block; 
 	padding:0px 10px; } 
 #value { color:#f90; }
 #saving { color:#0a0; }
 #error { color:#a00; font-weight:bold; }
 </style> 
</head><body>

<div class="button" id="pull">Pull Data</div> 
<div class="button" id="push">Push Reload</div> 
<div class="results">
</div> 

</body></html> 