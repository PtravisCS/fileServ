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

?>


<!DOCTYPE html>
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
            <li><a href="main.php" class="navbar navbar-text">Files</a></li>
            <li><a href="chores.php" class="navbar navbar-text">Chores</a></li>
            <li><a href="mc.php" class="navbar navbar-text">Minecraft</a></li>
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
    <div class="row justify-content-center">
      <div class="col">
        <div class="card bg-light">
          <div class="card-header">
            <h3 class="card-title">Update Password</h3>
          </div>
          <div class="card-body">
            <a href="updatePassword.php" class="card-text btn btn-primary">Update Password</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card bg-light">
          <div class="card-header">
            <h3 class="card-title">Update Profile Picture</h3>
          </div>
          <div class="card-body">
            <a href="updatePicture.php" class="card-text btn btn-primary">Update Profile Picture</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card bg-light">
          <div class="card-header">
            <h3 class="card-title">Delete User</h3>
          </div>
          <div class="card-body">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
              Delete User
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span class="text-danger">This will perminantly delete your user and all files, delete anyways?</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="deleteUser.php" class="btn btn-danger">Delete User</a>
      </div>
    </div>
  </div>
</div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
