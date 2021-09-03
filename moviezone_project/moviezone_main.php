<?php
/*-------------------------------------------------------------------------------------------------
@Module: bv_caryard_main.php
This server-side main module interacts with UI to process user's requests

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/
require_once('moviezone_config.php'); 

/*initialize the model and view
*/
$model = new MovieZoneModel();
$view = new MovieZoneView();
$controller = new MovieZoneController($model, $view);
/*interacts with UI via GET/POST methods and process all requests
*/
if (!empty($_REQUEST[CMD_REQUEST])) { //check if there is a request to process
	$request = $_REQUEST[CMD_REQUEST];
	$controller->processRequest($request);
}
?>