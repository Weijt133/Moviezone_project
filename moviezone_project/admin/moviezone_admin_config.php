<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_admin_config.php
This server-side module defines all required settings and dependencies for the application

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
-------------------------------------------------------------------------------------------------*/

	/*define all required messages and commands for the session checking purpose
	*/
	//request and login/logout commands
	define ('CMD_REQUEST','request'); //the key to access submitted command via POST or GET
	define ('CMD_ADMIN_LOGIN', 'cmd_admin_login');
	define ('CMD_ADMIN_LOGOUT', 'cmd_admin_logout');
	
	//error messages	
	define ('ERR_SUCCESS', '_OK_'); //no error, command is successfully executed
	define ('ERR_AUTHENTICATION', "Wrong username or password");
	
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

	/*We use 'authorised' keyword to identify if the user hasn't logged in
	  if the keyword has not been set check if this is the login session then continue
	  if not simply terminate (a good security practice to check for eligibility
	  before execute any php code)
	*/
	if (empty($_SESSION['authorised'])) {
		//no authorisation so check if user is trying to log in
		if (empty($_REQUEST[CMD_REQUEST])||($_REQUEST[CMD_REQUEST] != CMD_ADMIN_LOGIN)) {
			//if no request or request is not login request
			die();
		}
	}
	/* ... continue the execution otherwise ... 
	(this is a good security practice to check for the eligibility before executing any code)
	*/
	
	/*This is a good practice to define all constants which may be used at different places*/
//	define ('DB_CONNECTION_STRING', "mysql:host=localhost;dbname=bv_caryard_db");
	define ('DB_CONNECTION_STRING', "mysql:host=localhost;dbname=jtang13_moviezone_db");
	define ('DB_USER', "jtang13");
	define ('DB_PASS', "22509385");
	define ('MSG_ERR_CONNECTION', "Open connection to the database first");
	define ('_MOVIE_PHOTO_FOLDER_', "../photos/");
	
	//user request commands
	define ('CMD_MOVIE_SELECT_ALL', 'cmd_movie_select_all');
	define ('CMD_MOVIE_FILTER', 'cmd_movie_filter'); //filter cars by submitted parameters
	define ('CMD_MOVIE_SELECT_BY_ID', 'cmd_movie_select_by_id'); //select cars by id (returns in JSON)
	define ('CMD_MOVIE_CHECK', 'cmd_movie_check'); //mark/unmark cars
	define ('CMD_MOVIE_ADD_FORM', 'cmd_movie_add_form'); //show form to add a movie
	define ('CMD_MOVIE_EDIT_FORM', 'cmd_movie_edit_form'); //show form to edit a movie
	define ('CMD_MOVIE_ADD', 'cmd_movie_add'); //add a movie
	define ('CMD_MOVIE_EDIT', 'cmd_movie_edit'); //edit a movie
	define ('CMD_MOVIE_DELETE', 'cmd_movie_delete'); //delete checked cars
    define ('CMD_LOAD_MOVIE_FORM', 'cmd_load_movie_form');
	define ('CMD_RENTALPERIOD_SELECT_ALL', 'cmd_rentalperiod_select_all');
    define ('CMD_DIRECTOR_SELECT_ALL', 'cmd_director_select_all');
    define ('CMD_STUDIO_SELECT_ALL', 'cmd_studio_select_all');
    define ('CMD_GENRE_SELECT_ALL', 'cmd_genre_select_all');
    define ('CMD_CLASSIFICATION_SELECT_ALL', 'cmd_classification_select_all');
    define ('CMD_ACTOR_SELECT_ALL', 'cmd_actor_select_all');
    define ('CMD_MOVIE_SHOW_ALL_PANEL', 'cmd_movie_show_all_panel'); //show all of members in the list
    define ('CMD_MEMBER_SHOW_ALL', 'cmd_member_show_all'); //show all of members in the list
    define ('CMD_MEMBER_SELECT_BY_ID', 'cmd_member_select_by_id'); //select members by id (returns in JSON)
    define ('CMD_LOAD_MEMBER_FORM', 'cmd_load_member_form');
    define ('CMD_MEMBER_EDIT', 'cmd_member_edit');
    define ('CMD_MEMBER_DELETE', 'cmd_member_delete'); //delete checked members
    define ('CMD_MEMBER_CREATETABLE', 'cmd_member_createtable');
    define('CMD_MEMBER_ADD','cmd_member_add');
	
	//application modules
	require_once('moviezone_admin_dba.php');
	require_once('moviezone_admin_model.php');
	require_once('moviezone_admin_view.php');
	require_once('moviezone_admin_controller.php');
?>