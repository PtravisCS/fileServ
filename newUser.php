<?php
  require 'database.php';
  
  if (!empty($_POST)) {
  
    $NameError = null;
    $PasswordError = null;
    $VerifyError = null;
    $CodeError = null;
    $access_level = 0;
    $profile_picture = "./default.png";

    $Name = $_POST['Name'];
    $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $Verify = password_hash($_POST['Verify'], PASSWORD_DEFAULT);
    $JoinCode = $_POST['JoinCode'];

    $valid = true;
   
    if (empty($Name)) {
      $NameError = 'Please Enter a User Name';
      $valid = false;
    }

    if (empty($_POST['Password'])) {
      $PasswordError = 'Please Enter a Password';
      $valid = false;
    }

    if (!(password_verify($_POST['Verify'], $Password))) {
      $VerifyError = 'Please Verify Your Password is Correct';
      $valid = false;
    }

    if (empty($JoinCode)) {
      $CodeError = 'Please Input the Join Code';
      $valid = false;
    } else if ($JoinCode != 'potato15cheese') { //yes this is insecure I know, but it's easy
      $CodeError = 'Incorrect Join Code';
      $valid = false;
    }


    if ($valid) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO Users (username,password,access_level,profile_picture) values(?, ?, ?, ?)"; $q = $pdo->prepare($sql);
      $q->execute(array($Name,$Password,$access_level,$profile_picture));
      Database::disconnect();
      header("location: login.php");
    }
    

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>

  <link href='css/bootstrap.min.css' rel='stylesheet'>  

</head>
<body>

  <div class='container'>
    <div class='span12'>

      <form action='newUser.php' method='post'>
        <h3>New User</h3>
        <div class='form-group'>
          <label for='Name'>Name</label>
          <input name='Name' id='Name' type='text' placeholder='Name' />
          <?php if(!empty($NameError)): ?>
            <span class='help-inline'><?php echo $NameError;?></span>
          <?php endif; ?>
        </div>
        <div class='form-group'>
          <label for='Password'>Password</label>
          <input name='Password' id='Password' type='password' placeholder='Password'/>
          <?php if(!empty($PasswordError)): ?>
            <span class='help-inline'><?php echo $PasswordError;?></span>
          <?php endif; ?>
        </div>
        <div class='form-group'>
          <label for='Verify'>Verify Password</label>
          <input name='Verify' id='Verify' type='password' placeholder='Password' />
          <?php if(!empty($VerifyError)): ?>
            <span class='help-inline'><?php echo $VerifyError;?></span>
          <?php endif; ?>
        </div>
        <div class='form-group'>
          <label for='JoinCode'>Join Code</label>
          <input name='JoinCode' id='JoinCode' type='text' placeholder='******' />
          <?php if(!empty($CodeError)): ?>
            <span class='help-inline'><?php echo $CodeError; ?></span>
          <?php endif; ?>
        </div>
        <div class='form-actions'>
          <button type='submit' class='btn btn-default'>Create User</button>
          <a class='btn' href='login.php'>Cancel</a>
        </div>
      </form>    

    </div>
  </div>

</body>
</html>
