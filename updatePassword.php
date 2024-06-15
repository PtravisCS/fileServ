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

  require '../shared_tools/database.php';
  require '../shared_tools/common_functions.php';

  if (!empty($_POST)) {
  
    $CurrentError = null;
    $PasswordError = null;
    $VerifyError = null;
    $access_level = 0;

    $Current = $_POST['Current'];
    $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $Verify = $_POST['Verify'];

    $valid = true;
   
    if (empty($Current)) {
      $CurrentError = 'Please Enter your Current Password';
      $valid = false;
    }

    if (empty($Password)) {
      $PasswordError = 'Please Enter a Password';
      $valid = false;
    }

    if (!(password_verify($_POST['Verify'], $Password))) {
      $VerifyError = 'Please Verify Your Password is Correct';
      $valid = false;
    }

    if ($valid) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT * FROM Users where username = ?";
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
      $sql = "UPDATE Users set password = ? Where username = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($Password,$Name));
      Database::disconnect();
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php bootstrap_css(); ?>
  <title>Update User</title>
</head>
<body>

  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <div class="navbar-header">
        <div class="dropdown">
          <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#">FileStore<span class="caret"></span></a>
          <ul class="dropdown-menu bg-dark">
            <li><a href="main.php" class="navbar navbar-text">Files</a></li>
            <li><a href="chores.php" class="navbar navbar-text">Chores</a></li>
            <li><a href="minecraft.php" class="navbar navbar-text">Minecraft</a></li>
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

  <div class="container">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Update User - Password</h3>
      </div>
      <div class="card-body">
        <form action="updatePassword.php" method="post">
          <div class="input-group mb-3">
            <label for="Current" class="input-group-text">Current Password</label>
            <input name="Current" id="Current" type="password" class="form-control" placeholder="Current Password" />
            <?php if(!empty($CurrentError)): ?>
              <span class="help-inline"><?php echo $CurrentError;?></span>
            <?php endif; ?>
          </div>
          <div class="input-group mb-3">
            <span for="Password" class="input-group-text">New Password</span>
            <input name="Password" id="Password" type="password" class="form-control" placeholder="New Password"/>
            <?php if(!empty($PasswordError)): ?>
              <span class="help-inline"><?php echo $PasswordError;?></span>
            <?php endif; ?>
          </div>
          <div class="input-group mb-3">
            <label for="Verify" class="input-group-text">Verify New Password</label>
            <input name="Verify" id="Verify" type="password" class="form-control" placeholder="New Password" />
            <?php if(!empty($VerifyError)): ?>
              <span class="help-inline"><?php echo $VerifyError;?></span>
            <?php endif; ?>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a class="btn btn-secondary" href="updateUser.php">Cancel</a>
          </div>
        </form>    
      </div>
    </div>
  </div>
  <?php bootstrap_js(); ?>
</body>
</html>
