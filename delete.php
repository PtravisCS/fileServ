<?php
  require '../shared_tools/database.php';
  require '../shared_tools/common_functions.php';

  session_start();
  check_logged_in();
  is_admin(); 

  $username = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];

  $id = 0;
  
  if (!empty($_GET['path'])) {
    
    $path = str_replace("'", "", $_GET['path']);

    if (!file_exists($path)) {

       $path = '';

       echo '<h2>Invalid File</h2>';
       die;
    }
  }
  else if (!empty($_POST['path'])) {
    $path = str_replace("'", "", $_POST['path']);

    if (!file_exists($path)) {

       $path = '';

       echo '<h2>Invalid File</h2>';
       die;
    }
  }

  if (!empty($_GET['id'])) {
	  $id = $_GET['id'];

    if (!is_numeric($id)) {

      $id = '';

      echo '<h2>Invalid ID</h2>';
      die;

    }
  }
  else if (!empty($_POST['id'])) {
	  $id = $_POST['id'];

    if (!is_numeric($id)) {

      $id = '';

      echo '<h2>Invalid ID</h2>';
      die;

    }
  }

  $filename = basename($path);

  if (!empty($_POST)) {
    unlink($path) or die('File Deletion Failed, Contact an Administrator');
	  
	  $pdo = Database::connect();
	  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  $sql = "DELETE FROM `Files` WHERE ID = ?";
	  $q = $pdo->prepare($sql);
	  $q->execute(array($id));
	  Database::disconnect();
	  header("Location: /fileServ/main.php");
  }
  
?>

<!DOCTYPE html>
<html>

  <head>
    <?php bootstrap_css(); ?> 
    <title>Delete File</title>
  </head>
  
  <body>
    <div class='container pt-4'>
      <div class='card'>
        <form class='form' action='delete.php' method='post'>
          <input type='hidden' name='id' value='<?php echo $id;?>'/>
          <input type='hidden' name='path' value='<?php echo $path;?>'/>

          <div class='card-header alert alert-danger'>
            Are you sure you wish to permenantly delete "<?php echo $filename  ?>"?
          </div>
          
          <div class='card-body text-center'>
            <div class='form-actions'>
              <button type="submit" class='btn btn-danger'>Yes</button>
              <a class="btn btn-primary" href='main.php'>No</a>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php bootstrap_js(); ?>
  </body>

</html>
