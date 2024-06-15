<?php
  require '../shared_tools/database.php';
  require '../shared_tools/common_functions.php';

  session_start();
  check_logged_in();
  is_admin(); 

  $username = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];
  
  $id = 0;

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

  $errormsg = '';

  if (!empty($_POST)) {
	  $pdo = Database::connect();
	  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT username FROM Users WHERE username = ?';
    $q = $pdo->prepare($sql);
    $q->execute(array($_POST['share_user']));
    $data = $q->fetchAll();

    if (isset($data[0]['username']) && $data[0]['username'] != $username) {

      $sql = 'INSERT INTO Share (username, file_ID, sharedWith) VALUES (?, ?, ?)';
      $q = $pdo->prepare($sql);
      $q->execute(array($_POST['username'], $_POST['id'], $_POST['share_user']));
      $data = $q->fetchAll();
      Database::disconnect();

    } else {
      $errormsg = 'Invalid Username';
    }
    Header('./main.php'); 
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
        <form class='form' action='share.php' method='post'>
          <input type='hidden' name='id' value='<?php echo $id;?>'/>
          <input type='hidden' name='username' value='<?php echo $username;?>'/>
          <?php
            if (!empty($_POST) || !empty($_GET)) {
              $pdo = Database::connect();
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $sql = "SELECT filename FROM `Files` WHERE ID = ?";
              $q = $pdo->prepare($sql);
              $q->execute(array($id));
              $data = $q->fetchAll();
              Database::disconnect();
            }
          ?>
          <div class='card-header alert <?php if($errormsg != '') { echo 'alert-danger'; } else { echo 'alert-info'; } ?>'>
            <?php
              if ($errormsg != '') {
                
                echo $errormsg;

              } else {

                foreach($data as $row) {
                  echo 'Sharing file: '.$row['filename'];
                }

              }
            ?>
          </div>
          
          <div class='card-body'>
            <div class='input-group mb-3'>
              <span class='input-group-text' id="username_label" />User to share with</span>
              <input type='text' name='share_user' class='form-control' placeholder='Username' aria-label='Username' aria-describedby="username_label"/>
            </div>
            <div class='form-actions'>
              <button type="submit" class='btn btn-danger'>Share</button>
              <a class="btn btn-primary" href='main.php'>Back</a>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php bootstrap_js(); ?>
  </body>

</html>
