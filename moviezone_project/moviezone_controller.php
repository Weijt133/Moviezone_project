<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_controller.php
This server-side module provides all required functionality to format and display cars in html

@Author: Junwei Tang
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/
require_once('moviezone_config.php'); 

class MovieZoneController {
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
    /*Loads top navigation panel*/
    public function loadTopNavPanel() {
        $this->view->topNavPanel();
    }
	/*Loads left navigation panel*/
	public function loadLeftNavPanel() {
		$this->view->leftNavPanel();
	}

	/*Loads top navigation panel*/
	public function loadFilterBarPanel() {
		$actors = $this->model->selectAllActors();
		$directors = $this->model->selectAllDirectors();
		$genres = $this->model->selectAllGenres();
        $classifications = $this->model->selectAllClassifications();
		if (($actors != null) && ($directors != null) && ($genres != null) && ($classifications != null)) {
			$this->view->filterBarPanel($actors, $directors, $genres, $classifications);
		}
		else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
	}

    /*Loads actor navigation panel*/
    public function loadActorBarPanel() {
        $actors = $this->model->selectAllActors();
        if ($actors != null) {
            $this->view->actorBarPanel($actors);
        }
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Loads director navigation panel*/
    public function loadDirectorBarPanel() {
        $directors = $this->model->selectAllDirectors();
        if ($directors != null) {
            $this->view->directorBarPanel($directors);
        }
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Loads genre navigation panel*/
    public function loadGenreBarPanel() {
        $genres = $this->model->selectAllGenres();
        if ($genres != null) {
            $this->view->genresBarPanel($genres);
        }
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Loads classification navigation panel*/
    public function loadClassificationBarPanel() {
        $classifications = $this->model->selectAllClassifications();
        if ($classifications != null) {
            $this->view->classificationsBarPanel($classifications);
        }
        else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Loads two random new release movie on left
    */
    public function loadLeftRandomMovieRequest() {
        $movies = $this->model->selectRandomMovies(2);
        if ($movies != null) {
            $this->view->showLeftRandomNewRelease($movies);
        } else {
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
            case CMD_TECHZONE:
                $this->handleTechzoneRequest();
                break;
            case CMD_CONTACT:
                $this->handleContactRequest();
                break;
            case CMD_HOME:
                $this->handleHomeRequest();
                break;
            case CMD_JOIN:
                $this->handleJoinRequest();
                break;
			case CMD_SHOW_TOP_NAV: 
				$this->loadFilterBarPanel();
				break;
            case CMD_SHOW_ACTOR_NAV:
                $this->loadActorBarPanel();
                break;
            case CMD_SHOW_DIRECTOR_NAV:
                $this->loadDirectorBarPanel();
                break;
            case CMD_SHOW_GENRE_NAV:
                $this->loadGenreBarPanel();
                break;
            case CMD_SHOW_CLASSIFICATION_NAV:
                $this->loadClassificationBarPanel();
                break;
			case CMD_MOVIE_SELECT_ALL:
				$this->handleSelectAllMovieRequest();
				break;
            case CMD_MOVIE_NEW_RELEASE:
                $this->handleNewReleaseMovieRequest();
                break;
			case CMD_MOVIE_SELECT_RANDOM:
				$this->handleSelectRandomMovieRequest();
				break;
			case CMD_MOVIE_FILTER:
				$this->handleFilterMovieRequest();
				break;
            case CMD_MEMBER_ADD:
                $this->handleMemberAddRequest();
                break;
            case CMD_USER_LOGIN_FORM:
                $this->handleLoginFormRequest();
                break;
            case CMD_USER_LOGIN:
                $this->handleUserLoginRequest();
                break;
            case CMD_USER_LOGOUT:
                $this->handleUserLogoutRequest();
                break;
            case CMD_MOVIE_CHECK:
                $this->handleMovieCheckRequest();
                break;
            case CMD_USER_CHECKOUT:
                $this->handleUserCheckoutRequest();
                break;
            default:
				$this->handleSelectRandomMovieRequest();
				break;
		}
	}
	/*Handles select all movies request
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
    /*Handles select new release movies request
    */
    private function handleNewReleaseMovieRequest() {
        $movies = $this->model->newReleaseMovies();
        if ($movies != null) {
            $this->view->showMovies($movies);
        } else {
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }
    /*Handles select left random movies request
	*/
    private function handleTechzoneRequest() {
        print file_get_contents('html/techzone.html');
    }
    /*Handles contact page request
	*/
    private function handleContactRequest() {
        print file_get_contents('html/contact.html');
    }
    /*Handles home page request
	*/
    private function handleHomeRequest() {
        print file_get_contents('html/home.html');
    }
    /*Handles join page request
	*/
    private function handleJoinRequest() {
        print file_get_contents('html/join_form.html');
    }
    /*Handles show login page request
	*/
    private function handleLoginFormRequest() {
        if (isset($_SESSION['member_id'])) {
            //we use 'authorised' keyword to identify if the user hasn't logged in
            //if the keyword has been set, simply redirect user to index admin page
            //header("Location: moviezoneClick()");
            die(); //and terminate
        }
        //otherwise, show the below login page

        print file_get_contents('html/login_form.html');
    }
    /*Handles user login request
	*/
    private function handleUserLoginRequest() {
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

        $result = $this->model->userLogin($user);

        if (!empty($result)) {
            //authorise user with the username to access
            $_SESSION['member_id'] = $result[0]['member_id'];
            $_SESSION['surname'] = $result[0]['surname'];
            $_SESSION['other_name'] = $result[0]['other_name'];

            /*and notify the caller about the successful login
             the notification protocol should be predefined so
             the client and server can understand each other
            */
            print ERR_SUCCESS; //send '_OK_' code to client
        } else {
            //not successful show error to user
            $error = $this->model->getError();
            if (!empty($error))
                $this->view->showError($error);
        }
    }

    /*Handles user logout request
	*/
    private function handleUserLogoutRequest() {
        // Unset all of the session variables.
        $_SESSION['member_id']=null;
        $_SESSION['surname']=null;
        $_SESSION["other_name"]=null;

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
        print ERR_SUCCESS;
    }

	/*Handles select random movies request
	*/
	private function handleSelectRandomMovieRequest() {
		$movies = $this->model->selectRandomMovies(MAX_RANDOM_MOVIES);
		if ($movies != null) {
			$this->view->showMovies($movies);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}		
	}
	/*Handles filter cars request
	*/
	private function handleFilterMovieRequest() {		
		$condition = array();		
		if (!empty($_REQUEST['actor']))
			$condition['actor'] = $_REQUEST['actor']; //submitted is make id and not actor name
		if (!empty($_REQUEST['director']))
			$condition['director'] = $_REQUEST['director']; //submitted is body id and not director name
		if (!empty($_REQUEST['genre']))
			$condition['genre'] = $_REQUEST['genre']; //submitted is state id and not genre name
		if (!empty($_REQUEST['classification']))
			$condition['classification'] = $_REQUEST['classification']; //submitted is state id and not state name
		//call the dbAdapter function
		$movies = $this->model->filterMovies($condition);
		if ($movies != null) {
			$this->view->showMovies($movies);
		} else {
			$error = $this->model->getError();
			if (!empty($error))
				$this->view->showError($error);
		}
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

    /*Handles check/uncheck movie request
	  We use session to remember checked movies
	*/
    private function handleMovieCheckRequest() {
        //$checked_movies is an associtive array with movie ids are the keys
        if (!empty($_REQUEST['movie_id'])) { //only if the request is valid
            if (empty($_SESSION['checked_movies']))
                $checked_movies = array(); //create new array
            else
                $checked_movies = $_SESSION['checked_movies']; //or retrive it from session storage
            //get the movie id from the request
            $movie_id =  (string)$_REQUEST['movie_id']; //convert to string is important
            if (empty($checked_movies[$movie_id])) { //check if movie_id already exists
                $checked_movies[$movie_id] = 1; //check
            } else {
                unset($checked_movies[$movie_id]); //uncheck by removing the movie id
            }
            //put the array in session so we can access next time
            $_SESSION['checked_movies'] = $checked_movies;
            //notify the client about the check/uncheck
            if (!empty($checked_movies[$movie_id]))
                print ERR_SUCCESS; //send _OK_ if checked
            //and send nothing back if unchecked
        }
    }

    /*Handles vheck out movie request
	  We use session to remember checked movies
	*/
    private function handleUserCheckoutRequest() {
        //$checked_movies is an associtive array with movie ids are the keys
        if (!empty($_SESSION['member_id'])) { //only if the request is valid
            if (!empty($_SESSION['checked_movies']))
                $movieids = array_keys($_SESSION['checked_movies']);
            $movies = $this->model->selectMovies($movieids);
            if ($movies == null) {
                $error = $this->model->getError();
                $this->view->showError($error);
            }
            $this->view->showCheckoutMovies($movies);
        }
    }
}
?>