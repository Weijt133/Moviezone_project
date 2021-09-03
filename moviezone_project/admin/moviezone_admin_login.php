<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_admin_login.php
This server-side module provides provides login UI for user authentication

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/
	
	/*Perform session checking, if already logged in then just put user through
	  otherwise, show login dialog */
	$php_version = phpversion();
	if (floatval($php_version) >= 5.4) {	
		if (session_status() == PHP_SESSION_NONE) {//need the session to start
			session_start();
		}
	} else {
		if (session_id() == '') {
			session_start();
		}
	}

	if (isset($_SESSION['authorised'])) {
		//we use 'authorised' keyword to identify if the user hasn't logged in
		//if the keyword has been set, simply redirect user to index admin page
		header("Location: moviezone_admin_index.php");
		die(); //and terminate
	}
	//otherwise, show the below login page
?>

<html>
	<head>
		<link rel='stylesheet' type='text/css' href='css/Moviezone_modal_dialog.css'>
		<script src="js/ajax.js"></script>
	</head>
<body>
	<!-- The Modal -->
	<div id='myModal' class='modal' style='padding-top: 250px;'>
	<form name='login'>
	<!-- Modal content -->
		<div class='modal-content' style='width: 500px;'>
			<div class='modal-header'>
				<!--<span class='close'>&times;</span>-->
				<span>Administrator Login</span>
			</div>
			<div class='modal-body'>
				Username: <input type='text' name='username'><br>
				Password: <input type='password' name='password'><br>
				<div id='id_error'></div>
			</div>
			<div class='modal-footer'>
				<button type='button' name='btnOK' id='id_OK' onclick='login_btnOKClicked();'>OK</button> 
				<button type='button' name='btnCancel' id='id_Cancel' onclick='login_btnCancelClicked();'>Cancel</button> 
			</div>
		</div>
	</form> 
	</div>
		<script>
			//simply goes back to the bv caryard index file index.php
			function login_btnCancelClicked() {
				window.location.replace('../index.php');
			}
			//send ajax request to ask for server-side authentication
			function login_btnOKClicked() {
				var formData = new FormData(document.login);
				makeAjaxPostRequest('moviezone_admin_main.php','cmd_admin_login',formData, success);
			}
			//handle the server response.
			function success(data) {
				if (data == '_OK_') { //ERR_SUCCESS == '_OK_' defined in bv_caryard_admin_config.php
					window.location.replace('moviezone_admin_index.php');
				} else {
					document.getElementById('id_error').innerHTML = data;
				}
			}
		</script>
</body>
</html>
