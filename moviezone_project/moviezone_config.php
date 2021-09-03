<?php
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


/*This is a good practice to define all constants which may be used at different places
*/
//modify suit your A2 database)
//define ('DB_CONNECTION_STRING', "mysql:host=localhost;dbname=jtang13_moviezone_db");
define ('DB_CONNECTION_STRING', "mysql:host=localhost;dbname=jtang13_moviezone_db");
define ('DB_USER', "jtang13");
define ('DB_PASS', "22509385");
define ('MSG_ERR_CONNECTION', "Open connection to the database first");

//maximum number of random cars will be shown
define ('MAX_RANDOM_MOVIES', 4);
//the folder where movie photos are stored
define ('_MOVIE_PHOTO_FOLDER_', "photos/");

//request command messages for client-server communication using AJAX
define ('CMD_REQUEST','request'); //the key to access submitted command via POST or GET
define ('CMD_TECHZONE', 'cmd_techzone'); //create and show techzone page
define ('CMD_CONTACT', 'cmd_contact'); //create and show techzone page
define ('CMD_HOME', 'cmd_home'); //create and show home page
define ('CMD_JOIN', 'cmd_join'); //create and show join page
define ('CMD_SHOW_TOP_NAV', 'cmd_show_top_nav'); //create and show top navigation panel
define ('CMD_SHOW_ACTOR_NAV', 'cmd_show_actor_nav'); //create and show top navigation panel
define ('CMD_SHOW_DIRECTOR_NAV', 'cmd_show_director_nav'); //create and show top navigation panel
define ('CMD_SHOW_GENRE_NAV', 'cmd_show_genre_nav'); //create and show top navigation panel
define ('CMD_SHOW_CLASSIFICATION_NAV', 'cmd_show_classification_nav'); //create and show top navigation panel
define ('CMD_MOVIE_SELECT_RANDOM', 'cmd_movie_select_random');
define ('CMD_MOVIE_SELECT_ALL', 'cmd_movie_select_all');
define ('CMD_MOVIE_NEW_RELEASE', 'cmd_movie_new_release');
define ('CMD_MOVIE_FILTER', 'cmd_movie_filter'); //filter cars by submitted parameters
define ('CMD_MEMBER_ADD', 'cmd_member_add'); //Add member details by submitted parameters
define ('CMD_USER_LOGIN_FORM', 'cmd_user_login_form'); //load user login form
define ('CMD_USER_LOGIN', 'cmd_user_login'); //user login function
define ('CMD_USER_LOGOUT', 'cmd_user_logout'); //user login function
define ('CMD_USER_CHECKOUT', 'cmd_user_checkout'); //user login function
define ('CMD_MOVIE_CHECK', 'cmd_movie_check'); //mark/unmark movies

//define error messages
define ('errSuccess', 'SUCCESS'); //no error, command is successfully executed
define ('errAdminRequired', "Login as admin to perform this task");

require_once('moviezone_dba.php');
require_once('moviezone_model.php');
require_once('moviezone_view.php');
require_once('moviezone_controller.php');

?>