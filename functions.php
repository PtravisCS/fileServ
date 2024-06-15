<?php

  function print_navbar($profile_picture, $username) {
    
    echo '
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
          </button>
          <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#">FileStore</span></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <span class="nav navbar-right">
            <a class="navbar-text dropdown-toggle" data-toggle="dropdown" href="#">' . '<img src="' . $profile_picture . '" height="25px" width="25px" />' . $username . '<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li>
                <a href="updateUser.php">Settings</a>
              </li>
              <li>
                <a href="logout.php">Log Out</a>
              </li>
            </ul>
          </span>
        </div>
      </div>
    </nav>
    ';

  }

  function check_logged_in() {

    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
          // redirect to your login page
          header("Location: login.php");
    }

  }

  function is_admin() {

    if ($_SESSION['access_level'] > 0) {
      define('ADMIN', TRUE);
    } else {
      define('ADMIN', FALSE);
    }

  } 

?>
