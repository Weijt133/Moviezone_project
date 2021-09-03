<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_admin_index.php
This server-side module provides main UI for the application (admin part)

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
-------------------------------------------------------------------------------------------------*/
require_once('moviezone_admin_main.php');
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/Moviezone_admin.css">
	<link rel="stylesheet" type="text/css" href="css/Moviezone_form.css">
	<script src="js/ajax.js"></script>
	<script src="js/moviezone_admin.js"></script>
</head>

<body>
<div id="id_container">
	<header>
		<h1>The MoviesZone - ADMINISTRATOR</h1>
		<h2><?php echo "(Logon as ".$_SESSION['authorised'].")"?></h2>
	</header>
	<!-- left navigation area -->
	<div id="id_left">
		<!-- load the navigation panel by embedding php code -->
		<?php $controller->loadLeftNavPanel()?>
	</div>
	<!-- right area -->	
	<div id="id_right">
		<!-- top navigation area -->
		<div id="id_topnav">
			<!-- the top navigation panel is loaded on demand using Ajax (see js code) -->
		</div>

		<div id="id_content"></div>
	</div>
	<!-- footer area -->
	<footer>Copyright &copy; WebDev-II (vinh.bui@scu.edu.au & william.smart@scu.edu.au) </footer>
</div>
</body>
</html>