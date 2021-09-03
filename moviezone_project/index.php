<?php 
/*-------------------------------------------------------------------------------------------------
@Module: index.php
This server-side module provides main UI for the application (admin part)

@Author: Junwei Tang
@Modified by: 
@Date: 09/09/2017
-------------------------------------------------------------------------------------------------*/
require_once('moviezone_main.php');
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/moviezone.css">
	<script src="js/ajax.js"></script>
	<script src="js/moviezone.js"></script>
</head>

<body>
<div id="id_container">
	<header>
		<h1>The MovieZone Emporium</h1>
		<div id="menutop">
			<!-- load the top navigation panel by embedding php code -->
			<?php $controller->loadTopNavPanel()?>
		</div>
	</header>
	<!-- left navigation area -->
	<div id="id_left">
		<!-- load the left navigation panel by embedding php code -->
		<?php $controller->loadLeftNavPanel()?>
		<?php $controller->loadLeftRandomMovieRequest()?>
	</div>
	<!-- right area -->	
	<div id="id_right">
		<?php
		if(!empty($_SESSION['member_id']))
			echo "<h3>".$_SESSION['other_name']."    ".$_SESSION['surname']."<br>logged-in<br></h3>";
		?>

		<!-- top navigation area -->
		<div id="id_topnav">			
			<!-- the top navigation panel is loaded on demand using Ajax (see js code) -->
		</div>
		<div id="id_content"></div>
	</div>
	<!-- footer area -->
	<footer>Copyright &copy; WebDev-II (j.tang.13@student.scu.edu.au) </footer>
</div>
</body>
</html>