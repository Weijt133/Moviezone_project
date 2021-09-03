<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_admin_controller.php
This server-side module provides all required functionality to format and display cars in html

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/

require_once('moviezone_admin_config.php');

class MovieZoneAdminController {
	private $model;
	private $view;
	
	/*Class contructor
	*/
	public function __construct($model, $view) {
		$this->model = $model;
		$this->view = $view;
	}
	
	/*Class destructor
	*/	
	public function __destruct() {
		$this->model = null;
		$this->view = null;
	}

    /*Loads top members panel for searching*/
    public function loadMemberSearchPanel() {
        $members = $this->model->selectAllMembers();
        if (($members != null) ) {
            $this->view->searchMemberPanel($members);
        }
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Loads top movies panel for searching*/
    public function loadMovieSearchPanel() {
        $movies = $this->model->selectAllMoviestable();
        if (($movies != null) ) {
            $this->view->searchMoviePanel($movies);
        }
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }
	
	/*Processes user requests and call the corresponding functions
	  The request and data are submitted via POST or GET methods
	*/
	public function processRequest($request) {
		switch ($request) {
			case CMD_ADMIN_LOGIN: 
				$this->handleAdminLoginRequest();
				break;
			case CMD_ADMIN_LOGOUT: 
				$this->handleAdminLogoutRequest();
				break;
			case CMD_MOVIE_FILTER:
				$this->handleFilterMovieRequest();
				break;					
			case CMD_MOVIE_SELECT_ALL:
				$this->handleSelectAllMovieRequest();
				break;
			case CMD_MOVIE_SELECT_BY_ID:
				$this->handleSelectMovieByIdRequest();
				break;				
			case CMD_MOVIE_CHECK:
				$this->handleMovieCheckRequest();
				break;
			case CMD_MOVIE_DELETE:
				$this->handleMovieDeleteRequest();
				break;
			case CMD_MOVIE_ADD:
				$this->handleMovieAddRequest();
				break;
			case CMD_MOVIE_EDIT:
				$this->handleMovieEditRequest();
				break;				
			case CMD_MOVIE_ADD_FORM:
				$this->handleShowMovieAddFormRequest();
				break;
			case CMD_MOVIE_EDIT_FORM:
				$this->handleShowMovieEditFormRequest();
				break;
            case CMD_LOAD_MOVIE_FORM:
                $this->handleShowMovieEditFormRequest();
                break;
			case CMD_RENTALPERIOD_SELECT_ALL:
				$this->handleSelectAllRentalPeriodRequest();
				break;
            case CMD_DIRECTOR_SELECT_ALL:
                $this->handleSelectAllDirectorsRequest();
                break;
            case CMD_STUDIO_SELECT_ALL:
                $this->handleSelectAllStudiosRequest();
                break;
            case CMD_GENRE_SELECT_ALL:
                $this->handleSelectAllGenresRequest();
                break;
            case CMD_CLASSIFICATION_SELECT_ALL:
                $this->handleSelectAllClassificationsRequest();
                break;
            case CMD_ACTOR_SELECT_ALL:
                $this->handleSelectAllActorsRequest();
                break;
            case CMD_MOVIE_SHOW_ALL_PANEL:
                $this->loadMovieSearchPanel();
                break;
            case CMD_MEMBER_CREATETABLE:
                $this->loadCreateMemberPage();
                break;
            case CMD_MEMBER_ADD:
                $this->handleMemberAddRequest();
                break;
            case CMD_MEMBER_SHOW_ALL:
                $this->loadMemberSearchPanel();
                break;
            case CMD_LOAD_MEMBER_FORM:
                $this->handleShowMemberEditFormRequest();
                break;
            case CMD_MEMBER_SELECT_BY_ID:
                $this->handleSelectMemberByIdRequest();
                break;
            case CMD_MEMBER_EDIT:
                $this->handleMemberEditRequest();
                break;
            case CMD_MEMBER_DELETE:
                $this->handleMemberDeleteRequest();
                break;
			default:
				break;
		}
	}
	
	/*Loads left navigation panel*/
	public function loadLeftNavPanel() {
		$this->view->leftNavPanel();
	}
	
	/* Notifies client machine about the outcome of operations
	   This is used for M2M communication when Ajax is used.
	*/
	private function notifyClient($code) {
		/*simply print out the notification code for now
		but in the future JSON can be used to encode the
		communication protocol between client and server
		*/		
		print $code;
	}
	
	/* Notifies client machine about the outcome of operations
	   This is used for M2M communication when Ajax is used.
	*/
	private function sendJSONData($data) {
		//using JSON
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	/*Handles admin login request
	*/
	private function handleAdminLoginRequest() {
		//take username and password and perform authentication
		//if successful, initialize the user session
		//echo 'OK';
		$keys = array('username','password');
		//retrive submiteed data
		$user = array();
		foreach ($keys as $key) {
			if (!empty($_REQUEST[$key])) {
				//more server side checking can be done here
				$user[$key] = $_REQUEST[$key];
			} else {
				//check required field
				$this->view->showError($key.' cannot be blank');
				return;
			}
		}			
		
		$result = $this->model->adminLogin($user);
		
		if ($result) {
			//authorise user with the username to access			
			$_SESSION['authorised'] = $user['username']; 
			
			/*and notify the caller about the successful login
			 the notification protocol should be predefined so
			 the client and server can understand each other
			*/
			$this->notifyClient(ERR_SUCCESS); //send '_OK_' code to client
		} else {
			//not successful show error to user
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}		
	}
	
		
	/*Handles admin logout request
	*/
	private function handleAdminLogoutRequest() {
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    //send '_OK_' code to client
    $this->notifyClient(ERR_SUCCESS);
}
	
	/*Handles select all cars request
	*/
	private function handleSelectAllMovieRequest() {
		$movies = $this->model->selectAllMovies();
		if ($movies != null) {
			$this->view->showMovies($movies);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}		
	}

	/*Handles select cars request
	*/
	private function handleSelectMovieByIdRequest() {
		if (!empty($_REQUEST['movie_id'])) {
			$movie_id = $_REQUEST['movie_id'];
			//call the dbAdapter function
			$movies = $this->model->searchMovie($movie_id);
			if ($movies != null) {
				$this->sendJSONData($movies);
			}		
		}
	}

    /*Handles join page request
    */
    private function loadCreateMemberPage() {
        print file_get_contents('html/join_form.html');
    }

    /*Handles add member request to add a new member*/
    private function handleMemberAddRequest() {
        $keys = array('surname','othername', 'contactmethod',  'mobilenum', 'phonenum', 'email', 'occupation',
            'streetaddr', 'suburbstate', 'postcode', 'joinusername', 'userpass', 'verifypass');
        //retrive submiteed data
        $memberdata = array();
        foreach ($keys as $key) {
            $memberdata[$key] = $_POST[$key];
        }
        $memberdata['magazine'] = isset($_POST['magazine']);

        if( $memberdata['userpass'] != $memberdata['verifypass']){
            $this->view->showError('Two passowrds should be verifity.');
        }

        //we will change it later to actual photo file name if photo upload is OK
        $result = $this->model->addMember($memberdata);
        if ($result != null)
            print ERR_SUCCESS ;
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Handles select members request
    */
    private function handleSelectMemberByIdRequest() {
        if (!empty($_REQUEST['member_id'])) {
            $member_id = $_REQUEST['member_id'];

            //call the dbAdapter function
            $members = $this->model->searchMember($member_id);
            if ($members != null) {
                $this->sendJSONData($members);
            }
        }
    }

    /**/
    private function handleMemberEditRequest() {
        $keys = array('updateid', 'surname','othername', 'userpass', 'occupation', 'contactmethod', 'email'
        , 'mobilenum','phonenum','streetaddr','suburbstate','postcode');

        $memberdata = array();
        //retrive submiteed data
        if(!empty($_POST['magazine']))
            $memberdata['magazine'] = $_POST['magazine'];
        else
            $memberdata['magazine']=0;

        foreach ($keys as $key) {
            if (!empty($_POST[$key])) {
                //more server side checking can be done here
                $memberdata[$key] = $_POST[$key];
            } else {
                //check required field
                $this->view->showError($key.' cannot be blank');
                return;
            }
        }
        $result = $this->model->updateMember($memberdata);
        if ($result != null)
            $this->notifyClient(ERR_SUCCESS);
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Handles delete member request
	*/
    private function handleMemberDeleteRequest() {
            $memberid = $_REQUEST['member_id'];
            //and call the corresponding model function to delete the cars
            $result = $this->model->deleteMemberById($memberid);
            if ($result == null) {
                $error = $this->model->getError();
                if ($error != null)
                    $this->view->showError($error);
            } else
                $this->notifyClient(ERR_SUCCESS);
    }
	
	/*Handles check/uncheck movie request
	  We use session to remember checked cars
	*/
	private function handleMovieCheckRequest() {
		//$checked_cars is an associtive array with movie ids are the keys
		if (!empty($_REQUEST['movie_id'])) { //only if the request is valid
			if (empty($_SESSION['checked_cars']))
				$checked_cars = array(); //create new array
			else
				$checked_cars = $_SESSION['checked_cars']; //or retrive it from session storage
			//get the movie id from the request
			$car_id =  (string)$_REQUEST['movie_id']; //convert to string is important
			if (empty($checked_cars[$car_id])) { //check if movie_id already exists
				$checked_cars[$car_id] = 1; //check
			} else {
				unset($checked_cars[$car_id]); //uncheck by removing the movie id
			}
			//put the array in session so we can access next time
			$_SESSION['checked_cars'] = $checked_cars; 
			//notify the client about the check/uncheck
			if (!empty($checked_cars[$car_id]))
				$this->notifyClient(ERR_SUCCESS); //send _OK_ if checked
				//and send nothing back if unchecked
		}
	}
	
	/*Handles delete movies request
	*/
	private function handleMovieDeleteRequest() {
        $movieid = $_REQUEST['movie_id'];
        //and call the corresponding model function to delete the cars
        $result = $this->model->deleteMoviesById($movieid);
        if ($result == null) {
            $error = $this->model->getError();
            if ($error != null)
                $this->view->showError($error);
        } else
            $this->notifyClient(ERR_SUCCESS);
	}

    /*Handles validate the movie data request */
    private function handleMovieValidateRequest($moviedata) {
        $key1s = array('title','year', 'tagline', 'plot');
        //retrive submiteed data
        foreach ($key1s as $key) {
            if (empty($moviedata[$key])) {
                //check required field
                $this->view->showError($key.' cannot be blank');
                return;
            }
        }

        if ($moviedata['director']=="none" && empty($moviedata['director_namenew'])) {
            //check required field
            $this->view->showError('Director is not valid.');
            return;
        }
        if ($moviedata['studio']=="none" && empty($moviedata['studio_namenew'])) {
            //check required field
            $this->view->showError('Studio is not valid.');
            return;
        }
        if ($moviedata['genre']=="none" && empty($moviedata['genre_namenew'])) {
            //check required field
            $this->view->showError('Genre is not valid.');
            return;
        }
        if ($moviedata['classification']=="none" && empty($moviedata['classification_namenew'])) {
            //check required field
            $this->view->showError('Classification is not valid.');
            return;
        }
        if ($moviedata['star1']=="none" && empty($moviedata['star1_namenew'])) {
            //check required field
            $this->view->showError('At less one actor in the movie.');
            return;
        }
        if ($moviedata['rental_period']=="none") {
            //check required field
            $this->view->showError('Please choose one of retal period.');
            return;
        }

        $key2s = array('DVD_rental_price', 'DVD_purchase_price','numDVD', 'numDVDout',
            'BluRay_rental_price', 'BluRay_purchase_price', 'numBluRay', 'numBluRayOut');
        //retrive submiteed data
        foreach ($key2s as $key) {
            if (empty($moviedata[$key])) {
                //check required field
                $this->view->showError($key.' cannot be blank');
                return;
            }
        }
        return true;
    }
	
	/*Handles add movie request to add a new movie*/
	private function handleMovieAddRequest() {
		$keys = array('title','year', 'tagline', 'plot', 'director', 'director_namenew', 'studio',
            'studio_namenew', 'genre', 'genre_namenew', 'classification', 'classification_namenew', 'star1'
            , 'star1_namenew', 'star2', 'star2_namenew', 'star3', 'star3_namenew', 'costar1',
            'costar1_namenew', 'costar2', 'costar2_namenew', 'costar3', 'costar3_namenew', 'rental_period',
            'DVD_rental_price', 'DVD_purchase_price','numDVD', 'numDVDout', 'BluRay_rental_price',
            'BluRay_purchase_price', 'numBluRay', 'numBluRayOut');
		//retrive submiteed data
		$moviedata = array();
		foreach ($keys as $key) {
            $moviedata[$key] = $_REQUEST[$key];
		}
		if($this->handleMovieValidateRequest($moviedata)==null)
		    return ;
		
		//we will change it later to actual photo file name if photo upload is OK
		$result = $this->model->addMovie($moviedata);
		if ($result != null)
			$this->notifyClient(ERR_SUCCESS);
		else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);			
		}
	}
	
	/**/
	private function handleMovieEditRequest() {
        $keys = array('movie_id', 'rental_period','dvdrental', 'dvdpurchase', 'dvdstock', 'dvdrented', 'blurental'
        , 'blupurchase','blustock','blurented');

        $moviedata = array();
        //retrive submiteed data

        foreach ($keys as $key) {
            if (!empty($_POST[$key])) {
                //more server side checking can be done here
                $moviedata[$key] = $_POST[$key];
            } else {
                //check required field
                $this->view->showError($key.' cannot be blank');
                return;
            }
        }
        $result = $this->model->updateMovie($moviedata);
        if ($result != null)
            $this->notifyClient(ERR_SUCCESS);
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
	}
	
	/**/
	private function handleShowMovieAddFormRequest() {
		print file_get_contents('html/movie_add_edit_form.html');
	}
	
	/**/
	private function handleShowMovieEditFormRequest() {
		//$checked_cars is an associtive array with movie ids are the keys
		if (!empty($_REQUEST['movie_id'])) { //only if the request is valid
			print file_get_contents('html/movie_edit_delete_form.html');
		}
	}

    /**/
    private function handleShowMemberEditFormRequest() {
        //$checked_cars is an associtive array with movie ids are the keys
        if (!empty($_REQUEST['member_id'])) { //only if the request is valid
            print file_get_contents('html/member_edit_delete_form.html');
        }
    }
	
	/* Handles the client request to get all states from the database
	   @return: all rental period in JSON format
	*/
	private function handleSelectAllRentalPeriodRequest() {
		$rental_period = $this->model->selectAllRentalPeriod();
		if ($rental_period != null) {
			$this->sendJSONData($rental_period);
		}
	}

    /* Handles the client request to get all states from the database
       @return: all directors in JSON format
    */
    private function handleSelectAllDirectorsRequest() {
        $director = $this->model->selectAllDirectors();
        if ($director != null) {
            $this->sendJSONData($director);
        }
    }
    /* Handles the client request to get all states from the database
       @return: all studios in JSON format
    */
    private function handleSelectAllStudiosRequest() {
        $studio = $this->model->selectAllStudios();
        if ($studio != null) {
            $this->sendJSONData($studio);
        }
    }

    /* Handles the client request to get all states from the database
       @return: all genres in JSON format
    */
    private function handleSelectAllGenresRequest() {
        $genre = $this->model->selectAllGenres();
        if ($genre != null) {
            $this->sendJSONData($genre);
        }
    }
    /* Handles the client request to get all states from the database
       @return: all classifications in JSON format
    */
    private function handleSelectAllClassificationsRequest() {
        $classification = $this->model->selectAllClassifications();
        if ($classification != null) {
            $this->sendJSONData($classification);
        }
    }
    /* Handles the client request to get all states from the database
       @return: all actors in JSON format
    */
    private function handleSelectAllActorsRequest() {
        $actor = $this->model->selectAllActors();
        if ($actor != null) {
            $this->sendJSONData($actor);
        }
    }


}
?>