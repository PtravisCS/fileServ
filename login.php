<?php

  require '../shared_tools/common_functions.php';
 
  session_start();
  if (isset($_SESSION['username']) || !empty($_SESSION['username'])) {
        header("Location: main.php");
  }

  require '../shared_tools/database.php';
  $username = null;
  $passwordError = null;
  $nameError = null;
  $valid = true;

  if (!empty($_POST)) { 
    $password = $_POST['password'];
    $username = $_POST['username'];
  }

  if (empty($password)) {
    //$passwordError = 'Incorrect Username or Password';
    $valid = false;
  }

  if (empty($username)) {
    //$passwordError = 'Please Enter a Valid Username or Password';
    $valid = false;
  }

  if ($valid) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM Users where username = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($username));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect(); 

    if (!empty($data['password'])) {
      $recieved = $data['password'];
      if(password_verify($password,$recieved)) {
        $access_level = $data['access_level'];
        $profile_picture = $data['profile_picture'];

      	session_start(['cookie_lifetime' => 86400,]);

	      $_SESSION['username'] = $username;
	      $_SESSION['access_level'] = $access_level; 
        $_SESSION['profile_picture'] = $profile_picture; 

	      header("Location: main.php"); 
	      
      } else {$passwordError = 'Incorrect Username or Password';}
    } else {$passwordError = 'Incorrect Username or Password';}
  } 

  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php bootstrap_css(); ?>
  <link href="css/mss.css" rel="stylesheet" />
  <title>PtServ</title>
</head>

<body>
  <div class="container vertical-align">
    <div class="card">
      <div class="card-header">
        <h4>Please Login</h4>
      </div>
      <div class="card-body">
        <form action"login.php" method="post">

            <div class="input-group mb-3">
              <span class="input-group-text" id="username_label" for="username">Username</span>
              <input name="username" id="username" type="text"  class="form-control" placeholder="username" required />
            </div>

            <div class="input-group mb-3">
              <label class="input-group-text" id="password_label" for="password">Password&nbsp;</label>
              <input name="password" id="password" type="password" class="form-control" placeholder="password" required />
            </div>
            
            <?php if (!empty($passwordError)) { echo '<p class="alert alert-danger">' . $passwordError . '</p>'; } ?>

            <button type="submit" class="btn btn-primary">Submit</button>

          </div>
        </form> 
      </div>
      <p class="">New User? <a href="newUser.php">Sign Up</a></p>
    </div>
  </div>

  <?php bootstrap_js(); ?>
</body>
</html>
