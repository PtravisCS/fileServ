<?php

  require '../shared_tools/database.php';
  require '../shared_tools/functions.php';

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
    $sql = 'SELECT filename, filePath FROM `Files` WHERE `ID`= ?';
    $q = $pdo->prepare($sql);
    $q->execute([$id]);
    $data = $q->fetchAll();
  } else {
    $sql = 'SELECT filename, filePath FROM `Files` WHERE `ID`= ? AND `username`= ?';
    $q = $pdo->prepare($sql);
    $q->execute([$id, $username]);
    $data = $q->fetchAll();
  }

  $fileName = $data[0]['filename'];

  $file = $data[0]['filePath'];

  $zip = new ZipArchive;
  $zip->open($file);
  $entries = $zip->count();
  $zip->close($file);

?>

<html>

  <head>
    <title><?php echo (isset($fileName)) ? $fileName: "No Name";?></title>
    <link rel='stylesheet' type='text/css' href='./css/mss.css' />
    <?php bootstrap_css(); ?>
  <head>

  <body>

    <?php print_navbar($profile_picture, $username); ?>

    <h2><?php echo (isset($fileName)) ? $fileName: "No Name";?></h2>

    <div>
      <ul>
      <?php
        $zip->open($file);
        for ($i = 0; $i < $entries; $i++) {

          $stat = $zip->statIndex($i);
          echo '<li>' . $stat['name'] . '</li>';

        }
        $zip->close();
      ?>
      </ul>
    </div>

    <?php bootstrap_js(); ?>
  </body>
</html>
