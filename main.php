<?php

  require '../shared_tools/database.php';
  require '../shared_tools/common_functions.php';

  session_start();
  check_logged_in();
  is_admin(); 

  $username = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];

  if (isset($_FILES["upload"]["name"])) {
    $target_dir = __DIR__ . "/files/".$username."/";
    $target_file = $target_dir . basename($_FILES["upload"]["name"]);
    $web_target_file = "./files/".$username."/".basename($_FILES["upload"]["name"]);
    $uploadOk = 1;
    $extension = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $filesize = $_FILES['upload']['size'];
    $filename = $_FILES['upload']['name'];
  }

  if (!isset($_POST["submit"])) {
    $uploadOk = 0;
    $error = "";
  } else if (file_exists($target_file)) {
    $uploadOk = 0;
    $error = "File Already Exists";
  }

  if ($uploadOk == 1) {
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0770, true);
    }
    if (!move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
      $uploadOk = 0;
    }
    if ($uploadOk == 1) {
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO Files (ID,username,filename,filesize,filetype,filePath) values(?, ?, ?, ?, ?, ?)"; 
      $q = $pdo->prepare($sql);
      $q->execute(array(null,$username,$filename,$filesize,$extension,$web_target_file));
      Database::disconnect();
    }
  }

  $pictures = array('jpg','png','gif','svg','tiff','bmp');
  $musics = array('wav','mp3','ogg');

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Main Page</title>
    <link rel='stylesheet' type='text/css' href='./css/mss.css' />
    <?php bootstrap_css(); ?>
  <head>

  <body>

    <?php print_navbar($profile_picture, $username); ?>

    <div class="container-fluid pt-3">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-4">
              <form action="main.php" method="post" enctype="multipart/form-data">
                <div class="input-group mb-3">
                  <input type="file" class="form-control" name="upload" id="upload">
                </div>
                <div class="input-group mb-3">
                  <input type="submit" class="btn btn-primary" value="Upload File" name="submit">
                </div>
              </form>
            </div>
          </div>
          </div>
          <div class="card-body">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <?php echo (ADMIN === True) ? '<th>Owner</th>' : ''; ?>
                  <th>Filename</th>
                  <th>File Size (Kilobytes)</th>
                  <th>File Type</th>
                  <th>Controls</th>
                </tr>
              </thead>
              <?php
                $pdo = Database::connect();
                if (ADMIN === TRUE) {
                  $sql = 'SELECT * FROM `Files` ORDER BY `filename`';
                  $data = $pdo->query($sql)->fetchAll();
                } else {
                  $sql = 'SELECT * FROM `Files` WHERE Files.username= ?
                   ORDER BY `filename`';
                  $q = $pdo->prepare($sql);
                  $q->execute([$username]);
                  $data = $q->fetchAll();
                }
                foreach($data as $row) {
                  echo '<tr>';
                    echo (ADMIN === True) ? '<td>'. $row['username'] . '</td>' : '';
                    echo '<td>'. $row['filename'] . '</td>';
                    echo '<td>'. bytes_to_human_readable($row['filesize']) . '</td>';
                    echo '<td>'. $row['filetype'] . '</td>';
                    echo '<td>';
                      echo '<div class="btn-group" role="group">';
                        echo (in_array(strtolower($row['filetype']), $pictures)) ? '<a class="btn btn-primary" href="'.$row['filePath'].'" target="_blank" >View</a>' : '';
                        echo (in_array(strtolower($row['filetype']), $musics)) ? '<a class="btn btn-primary" href="'.$row['filePath'].'" target="_blank" >Listen</a>' : '';
                        echo (in_array(strtolower($row['filetype']), ['txt','pdf'])) ? '<a class="btn btn-primary" href="'.$row['filePath'].'" target=_blank">Read</a>' : '';
                        echo (in_array(strtolower($row['filetype']), ['xml','xlsx', 'ods'])) ? '<a class="btn btn-primary" href="spreadsheet_reader.php?id=' . $row['ID'] .'" target=_blank">View</a>' : '';
                        echo (in_array(strtolower($row['filetype']), ['zip'])) ? '<a class="btn btn-primary" href="archive_viewer.php?id=' . $row['ID'] .'" target=_blank">See Contents</a>' : '';
                        echo '<a class="btn btn-success" href="'.$row['filePath'].'" download>Download</a>';
                        echo '<a class="btn btn-info" href="share.php?id='.$row['ID'].'">Share</a>';
                        echo '<a class="btn btn-danger" href="delete.php?id='.$row['ID'].'&path=\''.$row['filePath'].'\'">Delete</a>';
                      echo '</div>';
                    echo '</td>';
                  echo '</tr>';
                }
                Database::disconnect();
            ?>
          </table>
        </div>
      </div>
    </div>

    <?php bootstrap_js(); ?>
  </body>
</html>
