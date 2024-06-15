<?php
  require '../shared_tools/database.php';
  require '../shared_tools/common_functions.php';
  
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
  <?php bootstrap_css(); ?>
  <title>New User</title>
</head>
<body>

  <div class="container">
    <div class="card">
      <form action="newUser.php" method="post">

        <div class="card-header">
          <h4>New User</h4>
        </div>

        <div class="card-body">
          <div class="input-group mb-3">
            <span class="input-group-text" for="Name">Name</span>
            <input name="Name" id="Name" class="form-control" type="text" placeholder="Name" required />
            <?php if(!empty($NameError)): ?>
              <span class="help-inline"><?php echo $NameError;?></span>
            <?php endif; ?>
          </div>
          <div class="input-group mb-3">
            <span class="input-group-text" for="Password">Password</span>
            <input name="Password" id="Password" class="form-control" type="password" placeholder="Password"/>
            <?php if(!empty($PasswordError)): ?>
              <span class="help-inline"><?php echo $PasswordError;?></span>
            <?php endif; ?>
          </div>
          <div class="input-group mb-3">
            <span class="input-group-text" for="Verify">Verify Password</span>
            <input name="Verify" id="Verify" class="form-control" type="password" placeholder="Password" />
            <?php if(!empty($VerifyError)): ?>
              <span class="help-inline"><?php echo $VerifyError;?></span>
            <?php endif; ?>
          </div>
          <div class="input-group mb-3">
            <span class="input-group-text" for="JoinCode">Join Code</span>
            <input name="JoinCode" id="JoinCode" class="form-control" type="text" placeholder="******" />
            <?php if(!empty($CodeError)): ?>
              <span class="help-inline"><?php echo $CodeError; ?></span>
            <?php endif; ?>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a class="btn btn-danger" href="login.php">Cancel</a>
          </div>
        </div>
      </form>    
    </div>
  </div>
  <?php bootstrap_js(); ?>
</body>
</html>
