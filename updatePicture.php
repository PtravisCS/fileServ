<?php

  session_start();
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: login.php");
  }

  if ($_SESSION['access_level'] > 0) {
    define('ADMIN', TRUE);
  } else {
    define('ADMIN', FALSE);
  }
  $Name = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];

  require 'database.php';

  if (!empty($_POST)) {
  
    $CurrentError = null;
    $access_level = 0;

    $Current = $_POST['Current'];
    $Picture = $_POST['picture'];

    $valid = true;
   
    if (empty($Current)) {
      $CurrentError = 'Please Enter your Current Password';
      $valid = false;
    }

    if (empty($Picture)) {
      $PictureError = 'Please Select a Picture';
      $valid = false;
    }

    if ($valid) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT `password` FROM Users where username = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($Name));
      $data = $q->fetch(PDO::FETCH_ASSOC);
      Database::disconnect(); 

      if (!empty($data['password'])) {
        $recieved = $data['password'];
        if (!(password_verify($Current,$recieved))) {
          $valid = false;
          $CurrentError = 'Incorrect Password';
        }
      }
    }

    if ($valid) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "UPDATE Users set profile_picture = ? Where username = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($Picture,$Name));
      Database::disconnect();
      $_SESSION['profile_picture'] = $Picture; 
      header("location: updateUser.php");
    }
    
  }
  
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM `Files` WHERE `username`= ? ORDER BY `filename`";
  $q = $pdo->prepare($sql);
  $q->execute([$Name]);
  $data = $q->fetchAll();

  $pictures = array('jpg','png','gif','svg','tiff','bmp');

?>

<!Doctype HTML>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <title>Update User</title>
</head>
<body>

  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <div class="navbar-header">
        <div class="dropdown">
          <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#">FileStore<span class="caret"></span></a>
          <ul class="dropdown-menu bg-dark">
            <li><a href="main.php" class="navbar navbar-text">FileStore</a></li>
            <li><a href="taskAssignmentApp.php" class="navbar navbar-text">Task Assignment App</a></li>
          </ul>
        </div>
      </div>
      <div class="dropdown">
        <div class="nav navbar-right">
         <a class='navbar-text dropdown-toggle' data-toggle="dropdown" href="#"><?php echo "<img src='".$profile_picture."' height='25px' width='25px' />"." ".$Name ?><span class="caret"></span></a>
         <ul class="dropdown-menu bg-dark">
           <li>
             <a href='logout.php' class="navbar navbar-text">Log Out</a>
           </li>
         </ul>
        </div>
      </div>
    </div>
  </nav>

  <br />

  <div class='container '>
    <div class='row justify-content-center'>
      <div class="card bg-light">
        <div class="card-header">
          <h3 class="card-title">Update User - Password</h3>
        </div>
        <div class="card-body">
          <form action='updatePicture.php' method='post'>
            <div class='form-group row'>
              <label for='Current' class="col">Current Password</label>
              <input name='Current' id='Current' type='password' class="form-control col" placeholder='Current Password' />
              <?php if(!empty($CurrentError)): ?>
                <span class='help-inline'><?php echo $CurrentError;?></span>
              <?php endif; ?>
            </div>
            <div class='form-group row'>
              <label for='picture' class="col">Profile Picture</label>
              <select name='picture' id='picture' class="form-control col">
                <?PHP
                  foreach($data as $row) {
                    echo (in_array(strtolower($row['filetype']), $pictures)) ? '<option value="'. $row['filePath'] .'">'. $row['filename'] . '</option>' : '';
                  }
                  echo '<option value="./default.png">default.png</option>';
                ?>
              </select>
              <?php if(!empty($PictureError)): ?>
                <span class='help-inline'><?php echo $PictureError; ?></span>
              <?php endif; ?>
            </div>
            <div class='form-actions'>
              <button type='submit' class='btn btn-primary'>Update</button>
              <a class='btn btn-secondary' href='updateUser.php'>Cancel</a>
            </div>
          </form>    
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
