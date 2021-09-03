<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_admin_model.php
This server-side module provides all required functionality i.e. to select, update, delete cars

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/
require_once('moviezone_admin_config.php');

class MovieZoneAdminModel {
	private $error;
	private $dbAdapter;
	
	/* Add initialization code here
	*/
	public function __construct() {
		$this->dbAdapter = new DBAdaper(DB_CONNECTION_STRING, DB_USER, DB_PASS);
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
	
	/* Authenticates the admin user.	   
	*/
	public function adminLogin($user) {
		//for now we simply accept anyone with webdev2 password
		if ($user['password'] == 'webdev2') {
			$this->error = ERR_SUCCESS;			
			return true;
		} else {
			$this->error = ERR_AUTHENTICATION;
			return false;
		}
	}

	/*Add a new movie to the database
	*/
	public function addMovie($moviedata) {
		$result = null;
		$this->error = null; //reset the error first
		
		/*begin database transaction so we can rollback if error
		  since the task involves a number of related database operation
		  use transaction ensures the database integrity.
		*/
		$this->dbAdapter->dbOpen();
		$dbConn = $this->dbAdapter->getDbConnection();		
		$dbConn->beginTransaction();
		
		/*first, get the id of the director from its name or create a new director
		*/
		if($moviedata['director']=="none") {
            $director = array(
                'director_name' => $moviedata['director_namenew']
            );
            //assign director id to the cardata in place of the director name
            $moviedata['director'] = $this->dbAdapter->directorAdd($director);

        }

		/*next, get the id of the studio from its name or create a new studio
		*/
        if($moviedata['studio']=="none") {
            $studio = array(
                'studio_name' => $moviedata['studio_namenew']
            );
            //assign director id to the cardata in place of the director name
            $moviedata['studio'] = $this->dbAdapter->studioAdd($studio);

        }

        /*next, get the id of the genre from its name or create a new genre
		*/
        if($moviedata['genre']=="none") {
            $genre = array(
                'genre_name' => $moviedata['genre_namenew']
            );
            //assign director id to the cardata in place of the director name
            $moviedata['genre'] = $this->dbAdapter->genreAdd($genre);

        }

        /*next, get the id of the classification from its name or create a new classification
		*/
        if(!empty($moviedata['classification_namenew'])) {
            //assign director id to the cardata in place of the director name
            $moviedata['classification'] = $moviedata['classification_namenew'];

        }

        /*then save the uploaded movie photo with filename is movie+movieid
        */
        //save the photo and return the filename
        $photo_file = $this->saveMoviePhoto('photo_loader', _MOVIE_PHOTO_FOLDER_, 'movie'.rand(1000000,1999999), true);

        /* finally update the movie record with the actual photo file name
        */
        $moviedata['thumbpath'] = $photo_file;

		//print_r($cardata);
		/*then insert the movie data to movie table. the result is the last insert id,
          which is used to name the movie photo file
		*/
        $result = $movieid = $this->dbAdapter->movieAdd($moviedata);

        /*next, insert the relationship of actor and movie into actor_movie table
		*/
        if(!empty($moviedata['star1_namenew'])) {
            $actor = array(
                'actor_name' => $moviedata['star1_namenew']
            );
            $moviedata['star1'] = $this->dbAdapter->actorAdd($actor);
        }
        $movie_actor = array(
            'movie_id' => $movieid,
            'actor_id' => $moviedata['star1'],
            'role' => "star1"
        );
        //assign director id to the cardata in place of the director name
        $this->dbAdapter->movie_actorAdd($movie_actor);

        /*next, insert the relationship of actor and movie into actor_movie table
		*/
        if($moviedata['star2']!="none" || !empty($moviedata['star2_namenew'])) {
            if (!empty($moviedata['star2_namenew'])) {
                $actor = array(
                    'actor_name' => $moviedata['star2_namenew']
                );
                $moviedata['star2'] = $this->dbAdapter->actorAdd($actor);
            }
            $movie_actor = array(
                'movie_id' => $movieid,
                'actor_id' => $moviedata['star2'],
                'role' => "star2"
            );
            //assign director id to the cardata in place of the director name
            $this->dbAdapter->movie_actorAdd($movie_actor);
        }

        /*next, insert the relationship of actor and movie into actor_movie table
		*/
        if($moviedata['star3']!="none" || !empty($moviedata['star3_namenew'])) {
            if (!empty($moviedata['star3_namenew'])) {
                $actor = array(
                    'actor_name' => $moviedata['star3_namenew']
                );
                $moviedata['star3'] = $this->dbAdapter->actorAdd($actor);
            }
            $movie_actor = array(
                'movie_id' => $movieid,
                'actor_id' => $moviedata['star3'],
                'role' => "star3"
            );
            //assign director id to the cardata in place of the director name
            $this->dbAdapter->movie_actorAdd($movie_actor);
        }

        /*next, insert the relationship of actor and movie into actor_movie table
       */
        if($moviedata['costar1']!="none" || !empty($moviedata['costar1_namenew'])) {
            if (!empty($moviedata['costar1_namenew'])) {
                $actor = array(
                    'actor_name' => $moviedata['costar1_namenew']
                );
                $moviedata['costar1'] = $this->dbAdapter->actorAdd($actor);
            }
            $movie_actor = array(
                'movie_id' => $movieid,
                'actor_id' => $moviedata['costar1'],
                'role' => "costar1"
            );
            //assign director id to the cardata in place of the director name
            $this->dbAdapter->movie_actorAdd($movie_actor);
        }

        /*next, insert the relationship of actor and movie into actor_movie table
       */
        if($moviedata['costar2']!="none" || !empty($moviedata['costar2_namenew'])) {
            if (!empty($moviedata['costar2_namenew'])) {
                $actor = array(
                    'actor_name' => $moviedata['costar2_namenew']
                );
                $moviedata['costar2'] = $this->dbAdapter->actorAdd($actor);
            }
            $movie_actor = array(
                'movie_id' => $movieid,
                'actor_id' => $moviedata['costar2'],
                'role' => "costar2"
            );
            //assign director id to the cardata in place of the director name
            $this->dbAdapter->movie_actorAdd($movie_actor);
        }

        /*next, insert the relationship of actor and movie into actor_movie table
       */
        if($moviedata['costar3']!="none" || !empty($moviedata['costar3_namenew'])) {
            if (!empty($moviedata['costar3_namenew'])) {
                $actor = array(
                    'actor_name' => $moviedata['costar3_namenew']
                );
                $moviedata['costar3'] = $this->dbAdapter->actorAdd($actor);
            }
            $movie_actor = array(
                'movie_id' => $movieid,
                'actor_id' => $moviedata['costar3'],
                'role' => "costar3"
            );
            //assign director id to the cardata in place of the director name
            $this->dbAdapter->movie_actorAdd($movie_actor);
        }

		//check the result if all successful then commit the transaction otherwise we rollback
		/*if ($result != null) {
			$dbConn->commit();
		} else {
			$dbConn->rollback();
			$this->error = $this->dbAdapter->lastError();
		}*/
		
		$this->dbAdapter->dbClose();
		return $result;		
	}
	
	/*Update movie data in the database
	*/
	public function updateCar($cardata) {
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
		$make = array(
			'name' => $cardata['make']
		);
		$result = $this->dbAdapter->makeSelect($make);
		//assign make id to the cardata in place of the make name		
		if ($result == null) {
			$cardata['make'] = $this->dbAdapter->makeAdd($make);			
		} else {
			$cardata['make'] = $result[0]['make_id']; 
		}

		/*next, get the id of the body or create a new body
		*/
		$body = array(
			'name' => $cardata['body']
		);		
		$result = $this->dbAdapter->bodySelect($body);		
		if ($result == null) {
			$cardata['body'] = $this->dbAdapter->bodyAdd($body);		
		} else {
			$cardata['body'] = $result[0]['body_id']; 
		}
		
		/*then save the uploaded movie photo with filename is movie+carid
		*/		
		$photo_file = $this->saveMoviePhoto('photo_loader', _MOVIE_PHOTO_FOLDER_, 'movie'.$cardata['movie_id'], true);
		if ($photo_file != 'default.jpg')
			$cardata['photo'] = $photo_file;
			
		/* finally update the movie record with the actual photo file name
		*/
		$result = $this->dbAdapter->carUpdate($cardata);

		//check the result if all successful then commit the transaction otherwise we rollback
		if ($result != null) {
			$dbConn->commit();
		} else {
			$dbConn->rollback();
			$this->error = $this->dbAdapter->lastError();
		}
		
		$this->dbAdapter->dbClose();
		
		return $result;		
	}
	
	
	/*Selects all cars from the database
	*/
	public function selectAllMovies() {
		$this->error = null; //reset the error first
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->movieSelectAll();
		$this->dbAdapter->dbClose();
		if ($result == null)
			$this->error = $this->dbAdapter->lastError();
		
		return $result;
	}

    /*Selects all movies from the movie table
    */
    public function selectAllMoviestable() {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->movietableSelectAll();
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    public function searchMovie($movie_id) {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->movieSelect($movie_id);
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Update movie data in the database
   */
    public function updateMovie($moviedata) {
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
        $movie = array(
            'movie_id' => $moviedata['movie_id'],
            'rental_period' => $moviedata['rental_period'],
            'DVD_rental_price' => $moviedata['dvdrental'],
            'DVD_purchase_price' => $moviedata['dvdpurchase'],
            'numDVD' => $moviedata['dvdstock'],
            'numDVDout' => $moviedata['dvdrented'],
            'BluRay_rental_price' => $moviedata['blurental'],
            'BluRay_purchase_price' => $moviedata['blupurchase'],
            'numBluRay' => $moviedata['blustock'],
            'numBluRayOut' => $moviedata['blurented']
        );
        /* finally update the movie record with the actual photo file name
        */
        $result = $this->dbAdapter->movieUpdate($movie);

        //check the result if all successful then commit the transaction otherwise we rollback
        if ($result != null) {
            $dbConn->commit();
        } else {
            $dbConn->rollback();
            $this->error = $this->dbAdapter->lastError();
        }

        $this->dbAdapter->dbClose();

        return $result;
    }

    /*Delete all movies with given ids*/
    public function deleteMoviesById($movieid) {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        //first delete movie photos
        $result = $this->dbAdapter->movieDeletePhotoById($movieid);
        if ($result != null) {
            //then delete movie from database
            $result = $this->dbAdapter->movieDeleteById($movieid);
        }
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }
	
	/*Filter cars from the database
	*/
	public function filterCars($condition) {
		$this->error = null; //reset the error first
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->carFilter($condition);
		$this->dbAdapter->dbClose();
		if ($result == null)
			$this->error = $this->dbAdapter->lastError();
		
		return $result;
	}	
	
	/*Delete all cars with given ids*/
	public function deleteCarsById($carids) {
		$this->error = null; //reset the error first
		$this->dbAdapter->dbOpen();
		//first delete movie photos
		$result = $this->dbAdapter->carDeletePhotoById($carids);
		if ($result != null) {
			//then delete cars from database
			$result = $this->dbAdapter->carDeleteById($carids);
		}
		$this->dbAdapter->dbClose();
		if ($result == null)
			$this->error = $this->dbAdapter->lastError();

		return $result;
	}
	
	/*Return the list of rental period
	*/
	public function selectAllRentalPeriod() {
		$this->error = null; //reset the error first
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->rentalperiodSelectAll();
		$this->dbAdapter->dbClose();
		if ($result == null)
			$this->error = $this->dbAdapter->lastError();
		
		return $result;		
	}

    /*Return the list of actors
    */
    public function selectAllActors() {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->actorSelectAll();
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }
	
	/*Return the list of directors
	*/
	public function selectAllDirectors() {
		$this->error = null; //reset the error first
		$this->dbAdapter->dbOpen();
		$result = $this->dbAdapter->directorSelectAll();
		$this->dbAdapter->dbClose();
		if ($result == null)
			$this->error = $this->dbAdapter->lastError();
		
		return $result;		
	}

    /*Return the list of studios
    */
    public function selectAllStudios() {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->studioSelectAll();
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Return the list of genres
    */
    public function selectAllGenres() {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->genreSelectAll();
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Return the list of classifications
    */
    public function selectAllClassifications() {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->classificationSelectAll();
        $this->dbAdapter->dbClose();
        if ($result == null)
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

    /*Return the list of members
    */
    public function selectAllMembers() {
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->memberSelectAll();
        $this->dbAdapter->dbClose();
        $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Search member by member_id from the database
	*/
    public function searchMember($member_id) {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
        $result = $this->dbAdapter->memberSelect($member_id);
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }

    /*Update member data in the database
	*/
    public function updateMember($memberdata) {
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
            'member_id' => $memberdata['updateid'],
            'surname' => $memberdata['surname'],
            'other_name' => $memberdata['othername'],
            'password' => $memberdata['userpass'],
            'occupation' => $memberdata['occupation'],
            'contact_method' => $memberdata['contactmethod'],
            'email' => $memberdata['email'],
            'mobile' => $memberdata['mobilenum'],
            'landline' => $memberdata['phonenum'],
            'magazine' => $memberdata['magazine'],
            'street' => $memberdata['streetaddr'],
            'suburb' => $memberdata['suburbstate'],
            'postcode' => $memberdata['postcode']
        );
        /* finally update the movie record with the actual photo file name
        */
        $result = $this->dbAdapter->memberUpdate($member);

        //check the result if all successful then commit the transaction otherwise we rollback
        if ($result != null) {
            $dbConn->commit();
        } else {
            $dbConn->rollback();
            $this->error = $this->dbAdapter->lastError();
        }

        $this->dbAdapter->dbClose();

        return $result;
    }

    /*Delete member with given ids*/
    public function deleteMemberById($memberid) {
        $this->error = null; //reset the error first
        $this->dbAdapter->dbOpen();
            //then delete cars from database
        $result = $this->dbAdapter->memberDeleteById($memberid);
        $this->dbAdapter->dbClose();
        if ($result == null)
            $this->error = $this->dbAdapter->lastError();

        return $result;
    }
	
	/* This function receive the upload photo and save it to a directory on server 
	@params: 
 	+uploader: name of the file uploader (to be used with $_FILES
    +target_dir: the directory where the image will be saved
    +file_name: the target image file name
    +override: override the existing file if true
    +returns the destination filename is OK or default.jpg if error
	*/
	private function saveMoviePhoto($uploader, $target_dir, $filename, $override) {
		try {   
			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (!isset($_FILES[$uploader]['error']) || is_array($_FILES[$uploader]['error'])) {
				throw new RuntimeException('Invalid parameters.');
			}
			// Check $_FILES[$uploader]['error'] value.
			switch ($_FILES[$uploader]['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException('No file sent.');
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException('Exceeded filesize limit.');
				default:
					throw new RuntimeException('Unknown errors.');
			}
			// You should also check filesize here ( > 1 MegaBytes). 
			define ("MAX_FILE_SIZE", 10000000);
			if ($_FILES[$uploader]['size'] > MAX_FILE_SIZE) {
				throw new RuntimeException('Exceeded filesize limit.');
			}
			// DO NOT TRUST $_FILES[$uploader]['mime'] VALUE !!
			// Check MIME Type by yourself.
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $ext = array_search(
				$finfo->file($_FILES[$uploader]['tmp_name']),
				array(
					'jpg' => 'image/jpeg',
					'png' => 'image/png',
					'gif' => 'image/gif',
				),
				true
			)) {
				throw new RuntimeException('Invalid file format.');
			}
			// Check if file already exists
			$target_file = $target_dir . $filename . "." . $ext; //get the fullpath to the file
			if ((!$override) && (file_exists($target_file))) {
				throw new RuntimeException('File already exists');
			}
			// You should name it uniquely.
			// DO NOT USE $_FILES[$uploader]['name'] WITHOUT ANY VALIDATION !!
			// On this example, obtain safe unique name from its binary data.
			if (!move_uploaded_file($_FILES[$uploader]['tmp_name'], $target_file)) {
				throw new RuntimeException('Failed to move uploaded file.');
			}
		
			//return null for success
			return $filename . "." . $ext;
		
		} catch (RuntimeException $e) {
			//we don't throw exception, simply return the default file name
			//return $e->getMessage();
			return 'default.jpg';
		}
	}
}
?>