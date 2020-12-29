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
  require 'database.php';

  if (isset($_POST["action"])) {

    $action = $_POST["action"];

    if(isset($_POST["force"])) {
      $force =  $_POST["force"];
    } else {
      $force = "false";
    }

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "Select * From Minecraft where ID = 1"; 
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetch(PDO::FETCH_ASSOC);

    if ($action == "start") {
      $status = "<p>Error</p>";
      if ($data['running'] == 0 || $force == "force") {
        $old_path = getcwd();
        chdir('/media/main/home/electracion/bored-games-mc');
        exec('bash -c "exec nohup setsid ./start.bash > ./server_log.txt 2>&1 &"');

        sleep(2);
        $file = fopen("pid.txt", "r") or die("Unable to open file");
        $pid = fgets($file);
        $mcPid = fgets($file);
        fclose($file);

        chdir($old_path);

        $sql = "UPDATE Minecraft set running = 1, pid = ?, mcPid = ?  Where id = 1";
        $q = $pdo->prepare($sql);
        $q->execute(array($pid, $mcPid));

        $status = "<p>Server Started</p>";
      } else {
        $status = "<p>Server Already Running!</p>";
      }

    } else if ($action == "stop") {
      $status = "<p>Error</p>";
      if ($data['running'] == 1 || $force == "force") {
        $pid = $data['pid'];
        $mcPid = $data['mcPid'];
        if (!($pid == 0)) {
          //exec("kill -9 ".$pid);
          if (posix_kill($mcPid, 9)) {
            //if (posix_kill($pid, 9)) {
              $sql = "UPDATE Minecraft set running = 0, pid = 0 Where id = 1";
              $q = $pdo->prepare($sql);
              $q->execute();
              $status = "<p>Server Stopped</p>";
            //} else {
            //  echo "<p>Failed to stop server, Stopped start.bash</p>";
            //} 
          } else {
            $status =  "<p>Failed to stop server</p>";
          }
        } else {
          $status = "<p>Something is Fishy Preventing Shutdown to Protect System</p>";
        }
      } else {
        $status = "<p>Server Not Running!</p>";
      }
    }
    Database::disconnect();
  } else {
    $status = "<p>Action not set</p>";
  } 

?>

<html>
  <head>
    <title>PTServ MC Server</title>
    <?php
     /*
      <link rel='stylesheet' type='text/css' href='./css/mss.css' />
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
      <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
     */
    ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <link rel='stylesheet' type='text/css' href="css/mss.css"> -->
  <head>

  <body>

    <nav class="navbar navbar-dark bg-dark">
      <div class="container-fluid">
        <div class="navbar-header">
          <div class="dropdown">
            <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#">FileStore<span class="caret"></span></a>
            <ul class="dropdown-menu bg-dark">
              <li><a href="main.php" class="navbar navbar-text">Files</a></li>
              <li><a href="chores.php" class="navbar navbar-text">Chores</a></li>
            </ul>
          </div>
        </div>
        <div class="dropdown">
          <div class="nav navbar-right">
           <a class='navbar-text dropdown-toggle' data-toggle="dropdown" href="#"><?php echo "<img src='".$profile_picture."' height='25px' width='25px' />"." ".$username ?><span class="caret"></span></a>
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
    <br />
    <div class="container">
      <div class="col">
        <div class="card" >
          <div class="card-header">
            <h3>Control Panel</h3>
          </div>
          <div class="card-body">
            <div class="container">
	      <!--
              <form action="mc.php" method="post" enctype="multipart/form-data">
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" name="action" id="start" value="start">

                  <label class="form-check-label" for="start">Start</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="radio" class="form-check-input" name="action" id="stop" value="stop"> 

                  <label class="form-check-label" for="stop">Stop</label>
                </div>
                <div class="form-check form-check-inline">
                  <input type="checkbox" class="form-check-input" name="force" id="force" value="force"> 

                  <label class="form-check-label" for="force">Force</label>
                </div>
                <input type="submit" class="btn btn-primary" value="Go!" name="submit">
              </form>
              <h3>Status: </h3>
              <div class="card" style="width: 200px; height: 2em;">
               <span style="padding: 2px 0px 0px 1em;"><?php echo $status ?></span>
	      </div>
	      -->

	      <iframe width="100%" height="600px" src="https://64.85.148.16:8000/">

	      </iframe>

	      <br />

              <br />
              <h3>World File</h3>
              <ul>
                <li><a href="./files/world_bk_old.zip">Old</a></li>
                <li><a href="./files/world_bk.zip">Current</a></li>
              </ul>
            </div>
          </div>
        </div>


      </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
