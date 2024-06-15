<?php

  require '../shared_tools/database.php';
  require '../shared_tools/common_functions.php';

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
    $sql = 'SELECT filePath FROM `Files` WHERE `ID`= ' . $id . ' ORDER BY `filename`';
    $data = $pdo->query($sql)->fetchAll();
  } else {
    $sql = 'SELECT filePath FROM `Files` WHERE `ID`= ? AND `username`= ? ORDER BY `filename`';
    $q = $pdo->prepare($sql);
    $q->execute([$id, $username]);
    $data = $q->fetchAll();
  }

  $file = $data[0]['filePath'];

  require 'vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
  use PhpOffice\PhpSpreadsheet\Reader\Ods;

  if (preg_match('/\.ods/', $file)) {
    $reader = new Ods();
  } else {
    $reader = new Xlsx();
  }

  $reader->setReadDataOnly(TRUE);
  $spreadsheet = $reader->load($file);
  $worksheet = $spreadsheet->getActiveSheet();

?>


<html>

  <head>
    <title><?php echo (isset($fileName)) ? $fileName: "No Name";?></title>
    <link rel="stylesheet" href="./css/spreadsheet.css" />
    <link rel='stylesheet' type='text/css' href='./css/mss.css' />
    <?php bootstrap_css(); ?>
  </head>

  <body>

    <?php print_navbar($profile_picture, $username); ?>

    <div class="container">
      <table>

        <?php

          foreach ($worksheet->getRowIterator() as $row) {

            echo '<tr>' . PHP_EOL;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(TRUE);

            foreach ($cellIterator as $cell) {
              echo '<td>' . 
                $cell->getValue() .
                '</td>' . PHP_EOL;
            }
            echo '</tr>' . PHP_EOL;
          }
        
        ?>

      </table>
    </div>
  </body>
  <?php bootstrap_js(); ?>
</html>

<?php
  $worksheet = null;
?>

