<? 
?>

<!DOCTYPE html>
<html><head>
 <script src="../static/scrs/jquery.min.js"></script>
 <script type="text/javascript"> 
 $(document).ready(function() {
	$('body').on('click','.button',function() {
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
	}); 
 }); 
 </script> 

 <style type="text/css">
 * { padding:10px; margin:0px; } 
 body { font-family:helvetica; }
 body * { display:block; }
 .button { background-color:#06c; 
	color:#fff; 
	cursor:pointer; } 
 .button:hover { background-color:#09f; 
	color:#fff; } 
 #spacing, #value, #saving { color:#ddd; display:inline-block; 
 	padding:0px 10px; } 
 #value { color:#f90; }
 #saving { color:#0a0; }
 #error { color:#a00; font-weight:bold; }
 </style> 
</head><body>

<div class="button">Reload</div> 
<div class="results">
</div> 

</body></html> 