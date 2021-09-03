<?php
/*dbAdapter: this module acts as the database abstraction layer for the application
@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modify by:
@Version: 1.0
*/

/*Connection paramaters
*/
require_once('moviezone_config.php'); 

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
		try {
			$this->dbConn = new PDO($this->dbConnectionString, $this->dbUser, $this->dbPassword);
			// set the PDO error mode to exception
			$this->dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbError = null;
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

	/*Helper function:
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

    /*Select an existing movies from the movies table
    @param $movie_id is the movie_id in movie table
    @return: an array of movie object
    */
    public function moviesSelect($movieids) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        if ($this->dbConn != null) {
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                //stringnify the list of carids with comma separated
                $movieid_string = implode(",", $movieids);
                //(modify suit the A2 movie table)
                $sql = "SELECT * FROM movie WHERE movie_id IN ($movieid_string)";
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
                $smt = $this->dbConn->prepare('SELECT * FROM studio');
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

    /*Check the user by username and password
	*/
    public function userCheck($user) {
        $result = null;
        $this->dbError = null; //reset the error message before any execution
        $this->dbOpen();
        if ($this->dbConn != null) {
            try {
                //Make a prepared query so that we can use data binding and avoid SQL injections.
                $sql = "select member_id,surname,other_name from member where username='".$user['username']."' AND password='".$user['password']."'";
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
	
//	$result = $dbAdapter->carSelectRandom(2);	
//	$result = $dbAdapter->carSelectAll();	
//	$result = $dbAdapter->carFilter($movie);

//	$result = $dbAdapter->stateSelectAll();	
//	$result = $dbAdapter->makeSelectAll();	
//	$result = $dbAdapter->bodySelectAll();	

	if ($result != null)		
		print_r($result);
	else
		echo $dbAdapter->lastError();
	$dbAdapter->dbClose();
}

//execute the test
//testDBA();

?>