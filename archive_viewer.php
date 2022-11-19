<?php

  require 'database.php';
  require 'functions.php';

  session_start();
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: login.php");
  }
  $username = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];

  if ($_SESSION['access_level'] > 0) {
    define('ADMIN', TRUE);
  } else {
    define('ADMIN', FALSE);
  }

  if (!empty($_GET['id'])) {
    $id = str_replace("'", "", $_GET['id']);
  } else {
    exit("File ID not Provided");
  }

  $pdo = Database::connect();
  if (ADMIN === TRUE) {
    $sql = 'SELECT filename, filePath FROM `Files` WHERE `ID`= ' . $id . ' ORDER BY `filename`';
    $data = $pdo->query($sql)->fetchAll();
  } else {
    $sql = 'SELECT filename, filePath FROM `Files` WHERE `ID`= ? AND `username`= ? ORDER BY `filename`';
    $q = $pdo->prepare($sql);
    $q->execute([$id, $username]);
    $data = $q->fetchAll();
  }

  $fileName = $data[0]['filename'];

  $file = $data[0]['filePath'];

  $zip = new ZipArchive;
  $zip->open($file);
  $entries = $zip->count();

?>

<html>

  <head>
    <title><?php echo (isset($fileName)) ? $fileName: "No Name";?></title>
    <link rel='stylesheet' type='text/css' href='./css/mss.css' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
  <head>

  <body>

    <?php print_navbar($profile_picture, $username); ?>

    <h2><?php echo (isset($fileName)) ? $fileName: "No Name";?></h2>

    <div>
      <ul>
      <?php
      for ($i = 0; $i<$entries; $i++) {

        $stat = $zip->statIndex($i);
        echo '<li>' . $stat['name'] . '</li>';

      }

      $zip->close();
      ?>
      </ul>
    </div>

  </body>
</html>
