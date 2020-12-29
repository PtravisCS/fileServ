<?php
  session_start();
  if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        // redirect to your login page
        header("Location: login.php");
  }

  $username = $_SESSION['username'];
  $profile_picture = $_SESSION['profile_picture'];

?>
<html>
	<head>
		<title>Do Your Chores!</title>
    <link rel='stylesheet' type='text/css' href='./css/mss.css' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	</head>

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
            <li><a href="main.php">Files</a></li>
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

		<h2>This is today's straight dope</h2>
		<p>That is to say, here's who's doing what today</p>

		<?php
			$today = getdate();
			echo '<p>Today is the: ';
			echo $today['yday'];
			echo 'th day of the year.</p>';
			echo '<hr />';

			$day = $today['yday'];
			$mon = $today['mon'];
			$even = $day % 2;
			$prev = ($day - 1) % 2;

      //Troubleshooting echo '<p>'.$even.' '.$prev.'</p>';

			if ($even == 0) { #if today was even
				if ($prev != 0) { #and if yesterday wasn't even.
					$wood = 'Micah';
					$dishes = 'Aden';
					$cat = 'Aden';
				} else { #yesterday was even too
					$wood = 'Aden';
					$dishes = 'Micah';
					$cat = 'Micah';
				}
			} else if ($even == 1) { #today wasn't even
				if ($prev == 0) { #yesterday was even
					$wood = 'Aden';
					$dishes = 'Micah';
					$cat = 'Micah';
				} else { #yesterday wasn't even either
					$wood = 'Micah';
					$dishes = 'Aden';
					$cat = 'Aden';
				}
			}

			if ($mon % 2 == 1) {
				$bathroom = 'Aden';
			} else {
				$bathroom = 'Micah';
			}

			echo '<p>Wood: ';
			echo $wood;
			echo '</p>';
			
			echo '<p>Dishes: ';
			echo $dishes;
			echo '</p>';

			echo '<p>Cat: ';
			echo $cat;
			echo '</p>';

			echo '<p>If it is the first time you have been here since the first ';
			echo 'of the month, then it is ';
			echo $bathroom;
			echo '\'s turn to clean the bathroom</p>';

		?>
  
    <!-- Calendar Embed Code -->
    <iframe src="https://calendar.google.com/calendar/embed?height=650&amp;wkst=2&amp;bgcolor=%237986CB&amp;ctz=America%2FDetroit&amp;src=YzNjZ25wNjhoYXZlYmMxbHI4azgzNWVianNAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;src=ZW4udXNhI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&amp;color=%23EF6C00&amp;color=%23E4C441&amp;showTitle=1&amp;showPrint=0&amp;hl=en" style="border-width:0" width="800" height="650" frameborder="0" scrolling="no"></iframe> 

	</body>
</html>
