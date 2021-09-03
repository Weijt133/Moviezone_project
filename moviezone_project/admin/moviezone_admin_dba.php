<?php
/*dbAdapter: this module acts as the database abstraction layer for the application
@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modify by:
@Version: 1.0
*/

/*Connection paramaters
*/
require_once('moviezone_admin_config.php');

/* DBAdpater class performs all required CRUD functions for the application
*/
class DBAdaper {
	/*local variables
	*/	
	private $dbConnectionString;
	private $dbUser;
	private $dbPassword;
	private $dbConn; //holds connection object
	private $dbError; //holds last error message
	
	/* The class constructor
	*/	
	public function __construct($dbConnectionString, $dbUser, $dbPassword) {
		$this->dbConnectionString = $dbConnectionString;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
	}	

	/*Opens connection to the database
	*/
	public function dbOpen() {
		$this->dbError = null; //reset the error message before any execution
		try {
			$this->dbConn = new PDO($this->dbConnectionString, $this->dbUser, $this->dbPassword);
			// set the PDO error mode to exception
			$this->dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
		}
		catch(PDOException $e) {
			$this->dbError = $e->getMessage();
			$this->dbConn = null;
		}
	}

	/*Closes connection to the database
	*/
	public function dbClose() {
		//in PDO assigning null to the connection object closes the connection
		$this->dbConn = null;
	}

	/*Return last database error
	*/
	public function lastError() {
		return $this->dbError;
	}

	/* Returns the database connection so it can be accessible outside the dbAdapter class
	*/
	public function getDbConnection() {
		return $this->dbConn;
	}
	
	/*Creates required tables in the database if not already created
	  @return: TRUE if successful and FALSE otherwise
	*/
	public function dbCreate() {
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//table actor
				$sql = "CREATE TABLE IF NOT EXISTS `actor` (
						  `actor_id` int(10) NOT NULL AUTO_INCREMENT,
						  `actor_name` char(128) DEFAULT NULL,
						  PRIMARY KEY (`actor_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=333";
				$result = $this->dbConn->exec($sql);
				//table director
				$sql = "CREATE TABLE IF NOT EXISTS `director` (
						  `director_id` int(10) NOT NULL AUTO_INCREMENT,
						  `director_name` char(128) DEFAULT NULL,
						  PRIMARY KEY (`director_id`),
						  UNIQUE KEY `director_name` (`director_name`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74";
				$result = $this->dbConn->exec($sql);
				//table genre
				$sql = "CREATE TABLE IF NOT EXISTS `genre` (
						  `genre_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `genre_name` char(128) NOT NULL,
						  PRIMARY KEY (`genre_id`),
						  UNIQUE KEY `genre_name` (`genre_name`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13";
				$result = $this->dbConn->exec($sql);
				//table member
				$sql = "CREATE TABLE IF NOT EXISTS `member` (
						  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `surname` varchar(128) NOT NULL,
						  `other_name` varchar(128) NOT NULL,
						  `contact_method` varchar(10) NOT NULL DEFAULT '',
						  `email` varchar(40) DEFAULT '',
						  `mobile` varchar(40) DEFAULT '',
						  `landline` varchar(40) DEFAULT '',
						  `magazine` tinyint(1) NOT NULL DEFAULT '0',
						  `street` varchar(40) DEFAULT '',
						  `suburb` varchar(40) DEFAULT '',
						  `postcode` int(4) DEFAULT NULL,
						  `username` varchar(10) NOT NULL DEFAULT '',
						  `password` varchar(10) NOT NULL DEFAULT '',
						  `occupation` varchar(20) DEFAULT '',
						  `join_date` date NOT NULL,
						  PRIMARY KEY (`member_id`),
						  UNIQUE KEY `username` (`username`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23";
				$result = $this->dbConn->exec($sql);
				//table movie
				$sql = "CREATE TABLE IF NOT EXISTS `movie` (
						  `movie_id` int(10) NOT NULL AUTO_INCREMENT,
						  `title` varchar(45) NOT NULL,
						  `tagline` varchar(128) NOT NULL,
						  `plot` varchar(256) NOT NULL,
						  `thumbpath` varchar(40) NOT NULL,
						  `director_id` int(10) NOT NULL,
						  `studio_id` int(10) NOT NULL,
						  `genre_id` int(10) NOT NULL,
						  `classification` varchar(128) NOT NULL,
						  `rental_period` varchar(128) NOT NULL,
						  `year` int(4) NOT NULL,
						  `DVD_rental_price` decimal(4,2) NOT NULL DEFAULT '0.00',
						  `DVD_purchase_price` decimal(4,2) NOT NULL DEFAULT '0.00',
						  `numDVD` int(3) NOT NULL DEFAULT '0',
						  `numDVDout` int(3) NOT NULL DEFAULT '0',
						  `BluRay_rental_price` decimal(4,2) NOT NULL DEFAULT '0.00',
						  `BluRay_purchase_price` decimal(4,2) NOT NULL DEFAULT '0.00',
						  `numBluRay` int(3) NOT NULL DEFAULT '0',
						  `numBluRayOut` int(3) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`movie_id`),
						  UNIQUE KEY `tagline` (`tagline`),
						  UNIQUE KEY `plot` (`plot`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102";
				$result = $this->dbConn->exec($sql);
				//table movie_actor
				$sql = "CREATE TABLE IF NOT EXISTS `movie_actor` (
						  `movie_id` int(10) NOT NULL,
						  `actor_id` int(10) NOT NULL,
						  `role` varchar(10) NOT NULL,
						  PRIMARY KEY (`movie_id`,`actor_id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8";
				$result = $this->dbConn->exec($sql);
				//table studio
				$sql = "CREATE TABLE IF NOT EXISTS `studio` (
						  `studio_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `studio_name` char(128) NOT NULL,
						  PRIMARY KEY (`studio_id`),
						  UNIQUE KEY `studio_name` (`studio_name`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42";
				$result = $this->dbConn->exec($sql);
				//create movie_detail_view to simplify the movie selection
				$sql = "
					CREATE VIEW movie_detail_view AS 
					SELECT  movie.movie_id, title, tagline, plot, thumbpath,  
					GROUP_CONCAT(if(role = 'star1', actor_name, NULL)) AS 'star1', 
					GROUP_CONCAT(if(role = 'star2', actor_name, NULL)) AS 'star2', 
					GROUP_CONCAT(if(role = 'star3', actor_name, NULL)) AS 'star3',
					GROUP_CONCAT(if(role = 'costar1', actor_name, NULL)) AS 'costar1', 
					GROUP_CONCAT(if(role = 'costar2', actor_name, NULL)) AS 'costar2', 
					GROUP_CONCAT(if(role = 'costar3', actor_name, NULL)) AS 'costar3',
					director_name AS 'director', studio_name AS 'studio', genre_name AS genre,
				  classification, rental_period, year, DVD_rental_price,
				  DVD_purchase_price, numDVD, numDVDout, BluRay_rental_price,
				  BluRay_purchase_price, numBluRay, numBluRayOut
					FROM movie, actor, movie_actor, director, studio, genre 
				  WHERE movie.movie_id = movie_actor.movie_id 
				  AND movie_actor.actor_id = actor.actor_id 
				  AND movie.director_id = director.director_id 
				  AND movie.studio_id = studio.studio_id 
				  AND movie.genre_id = genre.genre_id 
				  AND movie.director_id = director.director_id 
				  AND movie.studio_id = studio.studio_id 
				  AND movie.genre_id = genre.genre_id 
					GROUP BY title, movie_id
						";
				$result = $this->dbConn->exec($sql);
				//create movie_actor_view to simplify the movie selection
				$sql = "
					CREATE VIEW movie_actor_view AS 
					  SELECT actor_name, movie_id FROM movie_actor, actor 
					  WHERE movie_actor.actor_id = actor.actor_ID ORDER BY actor_name
						";
				$result = $this->dbConn->exec($sql);				
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;	
	}
	
	/*------------------------------------------------------------------------------------------- 
                              DATABASE MANIPULATION FUNCTIONS
	-------------------------------------------------------------------------------------------*/

	/*Helper functions:
	Build SQL AND conditional clause from the array of condition paramaters
	*/
	protected function sqlBuildConditionalClause($params, $condition) {
		$clause = "";
		$and = false; //so we know when to add AND in the sql statement	
		if ($params != null) {
			foreach ($params as $key => $value) {
				$op = '='; //comparison operator
				if ($key == 'actor'){
					$clause = "WHERE star1 $op '$value' OR star2 $op '$value' OR star3 $op '$value' OR costar1 $op '$value' OR costar2 $op '$value' OR costar3 $op '$value'";
					$and = true;
					continue;
				}

				if (!empty($value)) {
					if ($and){
						$clause = $clause." $condition $key $op '$value'";
					} else {
						//the first AND condition
						$clause = "WHERE $key $op '$value'";
						$and = true;
					}
				}
			}
		}

		return $clause;
	}

	/*Select all existing movies from table
	@return: an array of matched movies
	*/
	public function movieSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT * FROM movie_detail_view');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select all existing movies from table
	@return: an array of matched movies
	*/
	public function movietableSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT * FROM movie');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select new releases movies from table
	@return: an array of matched movies
	*/
	public function movieNewRelease() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					'SELECT * FROM movie_detail_view where year>=2012');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select ramdom movies from the movie table
	@param: $max - the maximum number of cars will be selected
	@return: an array of matched cars (default 1 movie)
	*/
	public function movieSelectRandom($max=1) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare(
					"SELECT * 
					FROM movie_detail_view
					ORDER BY RAND() 
					LIMIT $max");
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select an existing movie from the movie table
	@param $condition: is an associative array of movie's details you want to match
	@return: an array of matched cars
	*/
	public function movieFilter($condition) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$sql = 'SELECT * FROM movie_detail_view '
					.$this->sqlBuildConditionalClause($condition, 'AND');
				$smt = $this->dbConn->prepare($sql);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}	
	
	
	/*Adds a movie to the movie table
		@param: $movie is an associative array of movie details
		@return: last-insert-id if successful and 0 (FALSE) otherwise
	*/	
	public function movieAdd($movie) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {		
			//Try and insert the movie, if there is a DB exception return
			//the error message to the caller.
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				$smt = $this->dbConn->prepare('INSERT INTO movie 
					(title, tagline, plot, thumbpath, director_id , studio_id, genre_id, classification, rental_period, year, DVD_rental_price, DVD_purchase_price, numDVD, numDVDout, BluRay_rental_price, BluRay_purchase_price, numBluRay, numBluRayOut) VALUES 
					(:title, :tagline, :plot, :thumbpath, :director_id , :studio_id, :genre_id, :classification, :rental_period, :year, :DVD_rental_price, :DVD_purchase_price, :numDVD, :numDVDout, :BluRay_rental_price, :BluRay_purchase_price, :numBluRay, :numBluRayOut)');
					
				//Bind the data from the form to the query variables.
				//Doing it this way means PDO sanitises the input which prevents SQL injection.
				$smt->bindParam(':title', $movie['title'], PDO::PARAM_STR);
				$smt->bindParam(':tagline', $movie['tagline'], PDO::PARAM_STR);
				$smt->bindParam(':plot', $movie['plot'], PDO::PARAM_STR);
				$smt->bindParam(':thumbpath', $movie['thumbpath'], PDO::PARAM_STR);
				$smt->bindParam(':director_id', $movie['director'], PDO::PARAM_INT);//director id
				$smt->bindParam(':studio_id', $movie['studio'], PDO::PARAM_INT); //studio id
				$smt->bindParam(':genre_id', $movie['genre'], PDO::PARAM_INT); //genre id
				$smt->bindParam(':classification', $movie['classification'], PDO::PARAM_STR); //classification
				$smt->bindParam(':rental_period', $movie['rental_period'], PDO::PARAM_STR);
				$smt->bindParam(':year', $movie['year'], PDO::PARAM_INT);
                $smt->bindParam(':DVD_rental_price', $movie['DVD_rental_price'], PDO::PARAM_STR);
                $smt->bindParam(':DVD_purchase_price', $movie['DVD_purchase_price'], PDO::PARAM_STR);
                $smt->bindParam(':numDVD', $movie['numDVD'], PDO::PARAM_INT);
                $smt->bindParam(':numDVDout', $movie['numDVDout'], PDO::PARAM_INT);
                $smt->bindParam(':BluRay_rental_price', $movie['BluRay_rental_price'], PDO::PARAM_STR);
                $smt->bindParam(':BluRay_purchase_price', $movie['BluRay_purchase_price'], PDO::PARAM_STR);
                $smt->bindParam(':numBluRay', $movie['numBluRay'], PDO::PARAM_INT);
                $smt->bindParam(':numBluRayOut', $movie['numBluRayOut'], PDO::PARAM_INT);

                //Execute the query and thus insert the movie
				$smt->execute();
				$result = $this->dbConn->lastInsertId();
				
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;
	}

	/*Select an existing movie from the movies table
	@param $movie_id is the movie_id in movie table
	@return: an movie object
	*/
	public function movieSelect($movie_id) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 movie table)
				$sql = 'SELECT * FROM movie WHERE movie_id='.$movie_id;
				$smt = $this->dbConn->prepare($sql);
				//Execute the query and thus insert the movie
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Deletes all existing movies with movie_id in the list from the movie table
	@params: $movie_id is an individual movie ids to be deleted
	@return: the number of cars have been deleted from database
	*/
	public function movieDeleteById($movie_id) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				$sql = "DELETE FROM movie WHERE movie_id =".$movie_id;
				$result = $this->dbConn->exec($sql);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}
		
	/*Deletes all existing cars with movie_id in the $carids list from the cars table
	@params: $movie_id is an array of movie ids to be deleted
	@return: the number of cars have been deleted from database
	*/	
	public function carDeleteById($carids) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {			
			try {				
				//stringnify the list of carids with comma separated
				$carid_string = implode(",", $carids);				
				//sql to delete a movie based on given params
				$sql = "DELETE FROM cars WHERE movie_id IN ($carid_string)";
				//AND card_id > 4 (hey you cannot delete Bill's and Vinh's cars :D)
				$sql = "DELETE FROM cars WHERE movie_id IN ($carid_string) AND (movie_id > 4)";
				$result = $this->dbConn->exec($sql);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}	
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;			
	}

	public function movieDeletePhotoById($movieid) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//stringnify the list of carids with comma separated

				$sql = "SELECT thumbpath FROM movie WHERE movie_id =".$movieid;
				$smt = $this->dbConn->prepare($sql);
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($result as $photo) {
					if (file_exists(_MOVIE_PHOTO_FOLDER_.$photo['thumbpath']))
						unlink(_MOVIE_PHOTO_FOLDER_.$photo['thumbpath']);
				}
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}
	
	public function carDeletePhotoById($carids) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {			
			try {				
				//stringnify the list of carids with comma separated
				$carid_string = implode(",", $carids);				
				//sql to delete a movie based on given params
				$sql = "SELECT photo FROM cars WHERE movie_id IN ($carid_string)";
				//AND card_id > 4 (hey you cannot delete Bill's and Vinh's cars :D)
				$sql = "SELECT photo FROM cars WHERE movie_id IN ($carid_string) AND (movie_id > 4)";
				$smt = $this->dbConn->prepare($sql);							  
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);	
				foreach ($result as $photo) {
					if (file_exists(_MOVIE_PHOTO_FOLDER_.$photo['photo']))
						unlink(_MOVIE_PHOTO_FOLDER_.$photo['photo']);
				}
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}	
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;			
	}

	/*Select all existing actor from the actor table
@return: an array of actors with column name as the keys;
*/
	public function actorSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM actor ORDER BY actor_name');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}


	/*Select all existing director from the directors table
	@return: an array of make with column name as the keys;
	*/
	public function directorSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM director ORDER BY director_name');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}


	/*Select all existing genre from the genre table
	@return: an array of genre with column name as the keys;
	*/
	public function genreSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM genre ORDER BY genre_name');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select all existing member from the member table
    @return: an array of member with column name as the keys;
    */
	public function classificationSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM movie GROUP BY classification');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

    /*Adds a member to the member table
        @param: $member is an associative array of mmenber details
        @return: last-insert-id if successful and 0 (FALSE) otherwise
    */
    public function memberAdd($member) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        if ($this->dbConn != null) {
            //Try and insert the movie, if there is a DB exception return
            //the error message to the caller.
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $smt = $this->dbConn->prepare('INSERT INTO member 
					(surname, other_name, contact_method, email, mobile, landline, magazine, street, suburb, postcode, username, password, occupation, join_date) VALUES 
					(:surname, :other_name, :contact_method, :email, :mobile, :landline, :magazine, :street, :suburb, :postcode, :username, :password, :occupation, :join_date)');

                //Bind the data from the form to the query variables.
                //Doing it this way means PDO sanitises the input which prevents SQL injection.
                $smt->bindParam(':surname', $member['surname'], PDO::PARAM_STR);
                $smt->bindParam(':other_name', $member['other_name'], PDO::PARAM_STR);
                $smt->bindParam(':contact_method', $member['contact_method'], PDO::PARAM_STR);
                $smt->bindParam(':email', $member['email'], PDO::PARAM_STR);
                $smt->bindParam(':mobile', $member['mobile'], PDO::PARAM_STR);//director id
                $smt->bindParam(':landline', $member['landline'], PDO::PARAM_STR); //studio id
                $smt->bindParam(':magazine', $member['magazine'], PDO::PARAM_INT); //genre id
                $smt->bindParam(':street', $member['street'], PDO::PARAM_STR); //classification
                $smt->bindParam(':suburb', $member['suburb'], PDO::PARAM_STR);
                $smt->bindParam(':postcode', $member['postcode'], PDO::PARAM_INT);
                $smt->bindParam(':username', $member['username'], PDO::PARAM_STR);
                $smt->bindParam(':password', $member['password'], PDO::PARAM_STR);
                $smt->bindParam(':occupation', $member['occupation'], PDO::PARAM_STR);
                $smt->bindParam(':join_date', $member['join_date'], PDO::PARAM_STR);

                //Execute the query and thus insert the movie
                $smt->execute();
                $result = $this->dbConn->lastInsertId();

            }catch (PDOException $e) {
                //Return the error message to the caller
                $this->dbError = $e->getMessage();
                $result = null;
            }
        } else {
            $this->dbError = MSG_ERR_CONNECTION;
        }

        return $result;
    }

	/*Select all existing member from the member table
    @return: an array of member with column name as the keys;
    */
	public function memberSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM member');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select an existing member from the members table
	@param $member_id is the member_id in member table
	@return: an member object
	*/
	public function memberSelect($member_id) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$sql = 'SELECT * FROM member WHERE member_id='.$member_id;
				$smt = $this->dbConn->prepare($sql);
				//Execute the query and thus insert the movie
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Updates an existing movie in the movies table
	@param: $movie is an associative array of movie details to be updated
	@return: TRUE if successful and FALSE if not
	*/
	function movieUpdate($movie) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			//Make a prepared query so that we can use data binding and avoid SQL injections.
			//(modify suit the A2 movie table)
			//Try and insert the movie, if there is a DB exception return the error message to the caller.
			try {
				$smt = $this->dbConn->prepare('UPDATE movie SET 
									rental_period = :rental_period,
									DVD_rental_price = :DVD_rental_price,
									DVD_purchase_price = :DVD_purchase_price,
									numDVD = :numDVD,
									numDVDout = :numDVDout,
									BluRay_rental_price = :BluRay_rental_price,
									BluRay_purchase_price = :BluRay_purchase_price,
									numBluRay = :numBluRay,
									numBluRayOut = :numBluRayOut
								WHERE movie_id = :movie_id');

				//Bind the data from the form to the query variables.
				//Doing it this way means PDO sanitises the input which prevents SQL injection.
				$smt->bindParam(':rental_period', $movie['rental_period'], PDO::PARAM_STR);
				$smt->bindParam(':DVD_rental_price', $movie['DVD_rental_price'], PDO::PARAM_STR);
				$smt->bindParam(':DVD_purchase_price', $movie['DVD_purchase_price'], PDO::PARAM_STR);
				$smt->bindParam(':numDVD', $movie['numDVD'], PDO::PARAM_INT);
				$smt->bindParam(':numDVDout', $movie['numDVDout'], PDO::PARAM_INT);
				$smt->bindParam(':BluRay_rental_price', $movie['BluRay_rental_price'], PDO::PARAM_STR); //classification
				$smt->bindParam(':BluRay_purchase_price', $movie['BluRay_purchase_price'], PDO::PARAM_STR);
				$smt->bindParam(':numBluRay', $movie['numBluRay'], PDO::PARAM_INT);
				$smt->bindParam(':numBluRayOut', $movie['numBluRayOut'], PDO::PARAM_INT);
				$smt->bindParam(':movie_id', $movie['movie_id'], PDO::PARAM_INT);

				//Execute the query and thus insert the movie
				$result = $smt->execute();
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Updates an existing member in the members table
	@param: $member is an associative array of movie details to be updated
	@return: TRUE if successful and FALSE if not
	*/
	function memberUpdate($member) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			//Make a prepared query so that we can use data binding and avoid SQL injections.
			//(modify suit the A2 member table)
			//Try and insert the movie, if there is a DB exception return the error message to the caller.
			try {
				$smt = $this->dbConn->prepare('UPDATE member SET 
									surname = :surname,
									other_name = :other_name,
									contact_method = :contact_method,
									email = :email,
									mobile = :mobile,
									landline = :landline,
									magazine = :magazine,
									street = :street,
									suburb = :suburb,
									postcode = :postcode,
									magazine = :magazine,
									occupation = :occupation
								WHERE member_id = :member_id');

				//Bind the data from the form to the query variables.
				//Doing it this way means PDO sanitises the input which prevents SQL injection.
				$smt->bindParam(':surname', $member['surname'], PDO::PARAM_STR);
				$smt->bindParam(':other_name', $member['other_name'], PDO::PARAM_STR);
				$smt->bindParam(':contact_method', $member['contact_method'], PDO::PARAM_STR);
				$smt->bindParam(':email', $member['email'], PDO::PARAM_STR);
				$smt->bindParam(':mobile', $member['mobile'], PDO::PARAM_STR);//director id
				$smt->bindParam(':landline', $member['landline'], PDO::PARAM_STR); //studio id
				$smt->bindParam(':magazine', $member['magazine'], PDO::PARAM_INT); //genre id
				$smt->bindParam(':street', $member['street'], PDO::PARAM_STR); //classification
				$smt->bindParam(':suburb', $member['suburb'], PDO::PARAM_STR);
				$smt->bindParam(':postcode', $member['postcode'], PDO::PARAM_INT);
				$smt->bindParam(':password', $member['password'], PDO::PARAM_STR);
				$smt->bindParam(':occupation', $member['occupation'], PDO::PARAM_STR);
				$smt->bindParam(':member_id', $member['member_id'], PDO::PARAM_INT);

				//Execute the query and thus insert the movie
				$result = $smt->execute();
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Deletes all existing members with member_id in the list from the member table
	@params: $member_id is an individual member ids to be deleted
	@return: the number of cars have been deleted from database
	*/
	public function memberDeleteById($member_id) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				$sql = "DELETE FROM member WHERE member_id =".$member_id;
				$result = $this->dbConn->exec($sql);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}

	/*Select all existing studio from the studio table
    @return: an array of studio with column name as the keys;
    */
	public function studioSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections.
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT * FROM studio ORDER By studio_name');
				//Execute the query
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}

		return $result;
	}
	
	/*Select all existing rental period from the movie table
	@return: an array of rental period with column name as the keys;
	*/
	public function rentalperiodSelectAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {		
			try {
				//Make a prepared query so that we can use data binding and avoid SQL injections. 
				//(modify suit the A2 member table)
				$smt = $this->dbConn->prepare('SELECT rental_period FROM movie GROUP BY rental_period');
				//Execute the query and thus insert the movie
				$smt->execute();
				$result = $smt->fetchAll(PDO::FETCH_ASSOC);	
				//use PDO::FETCH_BOTH to have both column name and column index
				//$result = $sql->fetchAll(PDO::FETCH_BOTH);
			}catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;			
	}

    /*Adds a actor to the actor table
        @param: $actor is an associative array of actor details
        @return: last-insert-id if successful and 0 (FALSE) otherwise
    */
    public function actorAdd($actor) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        if ($this->dbConn != null) {
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $smt = $this->dbConn->prepare('INSERT INTO actor (actor_name) VALUES (:actor_name)');

                //Bind the data from the form to the query variables.
                //Doing it this way means PDO sanitises the input which prevents SQL injection.
                $smt->bindParam(':actor_name', $actor['actor_name'], PDO::PARAM_STR);

                //Execute the query and thus insert the movie
                $smt->execute();
                $result = $this->dbConn->lastInsertId();

            }catch (PDOException $e) {
                //Return the error message to the caller
                $this->dbError = $e->getMessage();
                $result = null;
            }
        } else {
            $this->dbError = MSG_ERR_CONNECTION;
        }

        return $result;
    }

    /*Adds a director to the director table
        @param: $director is an associative array of director details
        @return: last-insert-id if successful and 0 (FALSE) otherwise
    */
    public function directorAdd($director) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        if ($this->dbConn != null) {
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $smt = $this->dbConn->prepare('INSERT INTO director (director_name) VALUES (:director_name)');

                //Bind the data from the form to the query variables.
                //Doing it this way means PDO sanitises the input which prevents SQL injection.
                $smt->bindParam(':director_name', $director['director_name'], PDO::PARAM_STR);

                //Execute the query and thus insert the movie
                $smt->execute();
                $result = $this->dbConn->lastInsertId();

            }catch (PDOException $e) {
                //Return the error message to the caller
                $this->dbError = $e->getMessage();
                $result = null;
            }
        } else {
            $this->dbError = MSG_ERR_CONNECTION;
        }

        return $result;
    }

    /*Adds a genre to the genre table
        @param: $genre is an associative array of genre details
        @return: last-insert-id if successful and 0 (FALSE) otherwise
    */
    public function genreAdd($genre) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        if ($this->dbConn != null) {
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $smt = $this->dbConn->prepare('INSERT INTO genre (genre_name) VALUES (:genre_name)');

                //Bind the data from the form to the query variables.
                //Doing it this way means PDO sanitises the input which prevents SQL injection.
                $smt->bindParam(':genre_name', $genre['genre_name'], PDO::PARAM_STR);

                //Execute the query and thus insert the movie
                $smt->execute();
                $result = $this->dbConn->lastInsertId();

            }catch (PDOException $e) {
                //Return the error message to the caller
                $this->dbError = $e->getMessage();
                $result = null;
            }
        } else {
            $this->dbError = MSG_ERR_CONNECTION;
        }

        return $result;
    }

    /*Adds a studio to the studio table
        @param: $studio is an associative array of studio details
        @return: last-insert-id if successful and 0 (FALSE) otherwise
    */
    public function studioAdd($studio) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        if ($this->dbConn != null) {
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $smt = $this->dbConn->prepare('INSERT INTO studio (studio_name) VALUES (:studio_name)');

                //Bind the data from the form to the query variables.
                //Doing it this way means PDO sanitises the input which prevents SQL injection.
                $smt->bindParam(':studio_name', $studio['studio_name'], PDO::PARAM_STR);

                //Execute the query and thus insert the movie
                $smt->execute();
                $result = $this->dbConn->lastInsertId();

            }catch (PDOException $e) {
                //Return the error message to the caller
                $this->dbError = $e->getMessage();
                $result = null;
            }
        } else {
            $this->dbError = MSG_ERR_CONNECTION;
        }

        return $result;
    }

    /*Adds a association of movie and actor to the movie_actor table
        @param: $movie_actor is an associative array of movie_actor details
        @return: last-insert-id if successful and 0 (FALSE) otherwise
    */
    public function movie_actorAdd($movie_actor) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
		$this->dbOpen();
		$dbConn = $this->getDbConnection();
		$dbConn->beginTransaction();
        if ($this->dbConn != null) {
            try {
            	/*$sql = 'INSERT INTO movie_actor (movie_id, actor_id, role) VALUES ('.$movie_actor['movie_id'].','.$movie_actor['actor_id'].',\''.$movie_actor['role'].'\')';
				$smt = $this->dbConn->prepare($sql);*/
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $smt = $this->dbConn->prepare('INSERT INTO movie_actor (movie_id, actor_id, role) VALUES (:movie_id, :actor_id, :role)');

                //Bind the data from the form to the query variables.
                //Doing it this way means PDO sanitises the input which prevents SQL injection.
                $smt->bindParam(':movie_id', $movie_actor['movie_id'], PDO::PARAM_INT);
                $smt->bindParam(':actor_id', $movie_actor['actor_id'], PDO::PARAM_INT);
                $smt->bindParam(':role', $movie_actor['role'], PDO::PARAM_STR);

                //Execute the query and thus insert the movie
                $smt->execute();
                $result = $this->dbConn->lastInsertId();

				/*echo var_dump($smt);
				print $movie_actor['movie_id']." ".$movie_actor['actor_id']." ".$movie_actor['role'];
				print "<br>".$result;
				print"success!!!!";*/

            }catch (PDOException $e) {
                //Return the error message to the caller
                $this->dbError = $e->getMessage();
                $result = null;
            }
        } else {
            $this->dbError = MSG_ERR_CONNECTION;
        }

        return $result;
    }

}


/*---------------------------------------------------------------------------------------------- 
                                         TEST FUNCTIONS
 ----------------------------------------------------------------------------------------------*/

 //Your task: implement the test function to test each function in this dbAdapter

 
/*Tests database functions
*/
function testDBA() {
	$dbAdapter = new DBAdaper(DB_CONNECTION_STRING, DB_USER, DB_PASS);
	
	$car = array(
	'photo' => 'car1.jpg',
	'price' => 2000.0,	
	'movie_id' => 7,
	'make_id' => 2,
	'body_id' => 2,
	'odometer' => 47000,
	'year' => 1970,
	'state_id' => 1,
	'title' => "Vinh's movie"
	);

	$dbAdapter->dbOpen();
	$dbAdapter->dbCreate();
	
//	$result = $dbAdapter->carAdd($movie);
//	$result = $dbAdapter->carSelect($movie);
//	$result = $dbAdapter->carSelectAll();	
//	$result = $dbAdapter->carUpdate($movie);
//	$result = $dbAdapter->carDelete($movie);

	$state = array(
	'state_id' => 9,
	'name' => 'New state 1'
	);
//	$result = $dbAdapter->stateAdd($state);
//	$result = $dbAdapter->stateSelect($state);	
//	$result = $dbAdapter->stateSelectAll();	
//	$result = $dbAdapter->stateUpdate($state);	
//	$result = $dbAdapter->stateDelete($state);	


	$make = array(
	'make_id' => 9,
	'name' => 'New make 1'
	);
//	$result = $dbAdapter->makeAdd($make);
//	$result = $dbAdapter->makeSelect($make);	
//	$result = $dbAdapter->makeSelectAll();	
//	$result = $dbAdapter->makeUpdate($make);	
//	$result = $dbAdapter->makeDelete($make);	

	$body = array(
	'body_id' => 9,
	'name' => 'New body type'
	);
//	$result = $dbAdapter->bodyAdd($body);
//	$result = $dbAdapter->bodySelect($body);	
//	$result = $dbAdapter->bodySelectAll();	
//	$result = $dbAdapter->bodyUpdate($body);	
//	$result = $dbAdapter->bodyDelete($body);	

	if ($result != null)		
		print_r($result);
	else
		echo $dbAdapter->lastError();
	$dbAdapter->dbClose();
}

//execute the test
//testDBA();

//-----------------------------EXPERIMENTAL FUNTIONS-----------------------------------------------
	/*Delete existing cars from the cars table
	@param: $condition is an associative array of movie details
	@return: the number of cars have been deleted from the database
	public function carDelete($condition) {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {			
			try {					
				// sql to delete a movie based on given params
				$sql = "DELETE FROM cars ".$this->sqlBuildConditionalClause($condition, "AND");	
				$result = $this->dbConn->exec($sql);				
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}	
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;	
	}
	*/
	
	/*Deletes all existing cars from the cars table
	@return: the number of cars have been deleted from database
	public function carDeleteAll() {
		$result = null;
		$this->dbError = null; //reset the error message before any execution
		if ($this->dbConn != null) {			
			try {					
				// sql to delete a movie based on given params
				$sql = "DELETE FROM cars";
				$result = $this->dbConn->exec($sql);
			} catch (PDOException $e) {
				//Return the error message to the caller
				$this->dbError = $e->getMessage();
				$result = null;
			}	
		} else {
			$this->dbError = MSG_ERR_CONNECTION;
		}
	
		return $result;	
	
	}
	*/
//--------------------------------------------------------------------------------------------------

?>
