
<?php 
	
	require 'recipeDatabase.php';

	if ( !empty($_POST)) {
		$nameError = null;
		$emailError = null;
		$mobileError = null;
		
		$name = $_POST['name'];
		$ingredients = $_POST['ingredients'];
		$directions = $_POST['directions'];
    $submitter = $_POST['submitter'];
    $tags = $_POST['tags'];
		

		$valid = true;
		if (empty($name)) {
			$nameError = 'Please enter a Name';
			$valid = false;
		}
		
		if (empty($ingredients)) {
			$ingredientsError = 'Please enter Ingredients';
			$valid = false;
		}
		
		if (empty($directions)) {
			$directionsError = 'Please enter directions';
			$valid = false;
    }

    if (empty($tags)) {
      $tags = "None";
    }
		
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO recipes (name,ingredients,directions,submittedBy,tags) values(?, ?, ?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($name,$ingredients,$directions,$submitter,$tags));
			Database::disconnect();
			header("Location: recipeMain.php");
		}
	}
?>

<html lang="en">
<head>
    <title>Menu Creator and Recipe System</title>
    <link   href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/mss.css">
            
</head>

<body>
    <div class="container">
    
        <div class="span10 offset1">
          <div class="row">
            <h3>Add a Recipe</h3>
          </div>
    		
          <form class="form-horizontal" action="create.php" method="post">
					  <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
					    <label class="control-label">Recipe Name</label>
					    <div class="controls">
					      	<input name="name" type="text"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
					      	<?php if (!empty($nameError)): ?>
					      		<span class="help-inline"><?php echo $nameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($ingredientsError)?'error':'';?>">
					    <label class="control-label">Ingredients (each on an individual line)</label>
					    <div class="controls">
					      	<textArea name="ingredients" placeholder="Ingredients" class="textArea-Wide"><?php echo !empty($ingredients)?$ingredients:'';?></textarea>
					      	<?php if (!empty($ingredientsError)): ?>
					      		<span class="help-inline"><?php echo $ingredientsError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($directionsError)?'error':'';?>">
					    <label class="control-label">Directions</label>
					    <div class="controls">
					      	<textArea name="directions" type="text"  placeholder="Directions" class="textArea-Wide"><?php echo !empty($directions)?$directions:'';?></textarea>
					      	<?php if (!empty($directionsError)): ?>
					      		<span class="help-inline"><?php echo $directionsError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($submitterError)?'error':'';?>">
					    <label class="control-label">From the Kitchen of</label>
					    <div class="controls">
					      	<input name="submitter" type="text"  placeholder="Chef's Name" value="<?php echo !empty($submitter)?$submitter:'';?>">
					      	<?php if (!empty($submitterError)): ?>
					      		<span class="help-inline"><?php echo $submitterError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  <div class="control-group <?php echo !empty($directionsError)?'error':'';?>">
					    <label class="control-label">Tags (One Per Line, CaSe SEnsiTive)</label>
					    <div class="controls">
					      	<textArea name="tags" type="text"  placeholder="Tags" class="textArea-Wide" ><?php echo !empty($tags)?$tags:'';?></textarea>
					      	<?php if (!empty($tagsError)): ?>
					      		<span class="help-inline"><?php echo $tagsError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>

					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Create</button>
						  <a class="btn" href="recipeMain.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->

    <script src="../js/nicEdit.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

  </body>
</html>
