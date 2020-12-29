<?php
  require 'database.php';
  $id = 0;
  
  if (!empty($_GET['path'])) {
	  $id = $_GET['id'];
    $file = str_replace("'", "", $_GET['path']);
  }

  if (!empty($_POST)) {
    echo $_POST['path'];
	  $id = $_POST['id'];
    $file = $_POST['path'];
    unlink($file) or die('File Deletion Failed, Contact an Administrator');
	  
	  $pdo = Database::connect();
	  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  $sql = "DELETE FROM `Files` WHERE ID = ?";
	  $q = $pdo->prepare($sql);
	  $q->execute(array($id));
	  Database::disconnect();
	  header("Location: ../main.php");
  }
  
?>

<!DOCTYPE html>
<html>

  <head>
    <link href='../css/bootstrap.min.css' rel='stylesheet'>
  </head>
  
  <body>
    <div class='container'>
	  <div class='span10 offset1'>
	    <div class='row'>
		  <h3>Delete a File</h3>
		</div>
		
		<form class='form' action='delete.php' method='post'>
		  <input type='hidden' name='id' value='<?php echo $id;?>'/>
		  <input type='hidden' name='path' value='<?php echo $file;?>'/>
		  <p class='alert alert-error'>Are You Sure You Wish to Delete ?</p>
		  <div class='form-actions'>
		    <button type='submit' class='btn btn-danger'>Yes</button>
			  <a class='btn primary' href='main.php'>No</a>
		  </div>
		</form>
	  </div>
	</div>
  </body>

</html>
