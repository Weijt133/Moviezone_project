<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_model.php
This server-side module provides all required functionality i.e. to select, update, delete cars

@Author: Junwei tang (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/
require_once('moviezone_config.php'); 

class MovieZoneModel {
	private $error;
	private $dbAdapter;
	
	/* Add initialization code here
	*/
	public function __construct() {
		$this->dbAdapter = new DBAdaper(DB_CONNECTION_STRING, DB_USER, DB_PASS);
		/* uncomment to create the database tables for the first time
		$this->dbAdapter->dbOpen();
		$this->dbAdapter->dbCreate();
		$this->dbAdapter->dbClose();
		*/
	}
	
	/* Add code to free any unused resource
	*/	
	public function __destruct() {
		$this->dbAdapter->dbClose();
	}
	
	/*Returns last error
	*/
	public function getError() {
		return $this->error;
	}
	
	/*Selects all cars from the database
	*/
	public function selectAllMovies() {
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->movieSelectAll();
		$this->dbAdapter->dbClose();
		$this->error = $this->dbAdapter->lastError();
		
		return $result;
	}

    /*Selects all cars from the database
    */
    public function newReleaseMovies() {
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->movieNewRelease();
        $this->dbAdapter->dbClose();
        $this->error = $this->dbAdapter->lastError();

        return $result;
    }
	
	/*Filter cars from the database
	*/
	public function filterMovies($condition) {
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->movieFilter($condition);
		$this->dbAdapter->dbClose();
		$this->error = $this->dbAdapter->lastError();
		
		return $result;
	}	
	
	/*Selects randomly a $max number of cars from the database
	*/
	public function selectRandomMovies($max) {
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->movieSelectRandom($max);
		$this->dbAdapter->dbClose();
		$this->error = $this->dbAdapter->lastError();
		
		return $result;
	}

    /*Select movies by movieids from the database
    */
    public function selectMovies($movieids) {
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->moviesSelect($movieids);
        $this->dbAdapter->dbClose();
        $this->error = $this->dbAdapter->lastError();

        return $result;
    }
	
	/*Return the list of actors
	*/
	public function selectAllActors() {
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->actorSelectAll();
		$this->dbAdapter->dbClose();
		$this->error = $this->dbAdapter->lastError();
		
		return $result;		
	}
	
	/*Return the list of director
	*/
	public function selectAllDirectors() {
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->directorSelectAll();
		$this->dbAdapter->dbClose();
		$this->error = $this->dbAdapter->lastError();
		
		return $result;		
	}
	
	/*Return the list of genres
	*/
	public function selectAllGenres() {
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->genreSelectAll();
		$this->dbAdapter->dbClose();
		$this->error = $this->dbAdapter->lastError();
		
		return $result;		
	}

    /*Return the list of classification
    */
    public function selectAllClassifications() {
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->classificationSelectAll();
        $this->dbAdapter->dbClose();
        $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Return the list of members
    */
    public function selectAllMembers() {
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->memberSelectAll();
        $this->dbAdapter->dbClose();
        $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Return the list of studio
    */
    public function selectAllStudios() {
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->studioSelectAll();
        $this->dbAdapter->dbClose();
        $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Add a new member to the database
	*/
    public function addMember($memberdata) {
        $result = null;
        $this->error = null; //reset the error first

        /*begin database transaction so we can rollback if error
          since the task involves a number of related database operation
          use transaction ensures the database integrity.
        */
        $this->dbAdapter->dbOpen();
        $dbConn = $this->dbAdapter->getDbConnection();
        $dbConn->beginTransaction();

        /*first, get the id of the make from its name or create a new make
        */
        $member = array(
            'surname' => $memberdata['surname'],
            'other_name' => $memberdata['othername'],
            'contact_method' => $memberdata['contactmethod'],
            'email' => $memberdata['email'],
            'mobile' => $memberdata['mobilenum'],
            'landline' => $memberdata['phonenum'],
            'magazine' => $memberdata['magazine'],
            'street' => $memberdata['streetaddr'],
            'suburb' => $memberdata['suburbstate'],
            'postcode' => $memberdata['postcode'],
            'username' => $memberdata['joinusername'],
            'password' => $memberdata['userpass'],
            'occupation' => $memberdata['occupation'],
            'join_date' => date("Y-m-d")
        );

        //print_r($cardata);
        /*then insert the car data to car table. the result is the last insert id,
          which is used to name the car photo file
        */
        $memberid = $this->dbAdapter->memberAdd($member);

        //check the result if all successful then commit the transaction otherwise we rollback
        if ($memberid != null) {
            $dbConn->commit();
        } else {
            $dbConn->rollback();
            $this->error = $this->dbAdapter->lastError();
        }

        $this->dbAdapter->dbClose();
        return $result;
    }

    /* Authenticates the normal user for checkout function.
	*/
    public function userLogin($user) {
        $result = $this->dbAdapter->userCheck($user);
        if ($result != null) {
            $this->error = ERR_SUCCESS;
            return $result;
        } else {
            $this->error = ERR_AUTHENTICATION;
            return ;
        }
    }

}
?>