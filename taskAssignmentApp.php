
<?php

  session_start();
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        // redirect to your login page
        header("Location: login.php");
  }

  if ($_SESSION['access_level'] > 0) {
    define('ADMIN', TRUE);
  } else {
    define('ADMIN', FALSE);
  }

  $username = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];
 
?>


<html>
  <head>
    <title>Main Page</title>
    <link rel='stylesheet' type='text/css' href='./css/mss.css' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <!-- <link rel='stylesheet' type='text/css' href="css/mss.css"> -->
  <head>

  <body>

    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
          </button>
          <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#">FileStore<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="chores.php">Chores</a></li>
            <li><a href="mc.php">Minecraft</a></li>
          </ul>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <span class="nav navbar-right">
            <a class='navbar-text dropdown-toggle' data-toggle="dropdown" href="#"><?php echo "<img src='".$profile_picture."' height='25px' width='25px' />"." ".$username ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li>
                <a href="updateUser.php">Settings</a>
              </li>
              <li>
                <a href='logout.php'>Log Out</a>
              </li>
            </ul>
          </span>
        </div>
      </div>
    </nav>

  </body>
</html>
