<?php

  session_start();
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: login.php");
  }

  if (!empty($_GET['filePath'])) {
    $file = str_replace("'", "", $_GET['filePath']);
  }

  if (!empty($_POST)) {
	  $id = $_POST['id'];
    $file = $_POST['path'];
  }

  require 'vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

  $reader = new Xlsx();
  $reader->setReadDataOnly(TRUE);
  $spreadsheet = $reader->load($file);
  $worksheet = $spreadsheet->getActiveSheet();

?>


<html>

  <head>
    <title>Spreadsheet</title>
    <link rel="stylesheet" href="./css/spreadsheet.css" />
  </head>

  <body>

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

</html>

<?php
  $worksheet = null;
?>

