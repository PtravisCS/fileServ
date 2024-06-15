<?php
 
  session_start();
  if (isset($_SESSION['username']) || !empty($_SESSION['username'])) {
        // redirect to your login page
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
<html lang='en'>
<head>
  <!-- <link href='css/bootstrap.min.css' rel='stylesheet' />
  <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet' /> -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href='css/mss.css' rel='stylesheet' />
  <title>PtServ</title>
</head>

<body>
  <div class='container vertical-align'>
    <div class="row justify-content-center"> 
      <form action'login.php' method='post' class='form-horizontal card bg-light'>
        <h4 class='card-header'>Please Login</h4>
        
        <div class='card-body'>

          <div class='form group'>
            <label class='control-label' for='username'>User Name</label>
            <input name='username' id='username' type='text'  class='form-control' placeholder='username' />
          </div>

          <br />

          <div class='form group'>
            <label class='control-label' for='password'>Password</label>
            <input name='password' id='password' type='password' class='form-control' placeholder='password' />
          </div>
          
          <?php if (!empty($passwordError)) { echo '<p class="alert alert-danger">' . $passwordError . '</p>'; } ?>

        </div>

        <div class='card-footer'>
          <button type='submit' clas='btn'>Submit</button>
        </div>
      </form> 
      <p class=''>New User? <a href='newUser.php'>Sign Up</a></p>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
