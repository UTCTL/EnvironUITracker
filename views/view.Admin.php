<?
if(!isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']!=1) 
  header("Location: home"); 

require_once('models/model.Admin.php'); 
$admin = new Admin($dblink); 
$page = 'admin'; 
HTMLhead($page); 
HTMLnav($page); 

$admin->instantiateById($_SESSION['DESuid']);  
?>
<section>
 <form enctype="multipart/form-data" id="uploadform">
  <input type="file" multiple name="file[]" id="images" accept="image/*">
  <input type="hidden" name="action" value="upload">
  <input type="hidden" name="project" id="proj" value="">
 </form><progress></progress>
 <ul id="menu">
  <?
	echo $admin->getMenu(); 
  ?>
 </ul>
</section>

<div class="curtain" id="addProject">
 <div class="box">
  Add a project
  <input type="text" id="newProjectName" placeholder="Project Name"><br>
  <textarea id="newProjectDescription" placeholder="Description"></textarea>
  <input type="button" class="submit" id="addproject" value="Save">
  <a class="curtainClose" id="addProject">X</a>
 </div>
</div>

<?
HTMLfoot($page); 
?>