<?php
/*-------------------------------------------------------------------------------------------------
@Module: bv_caryard_view.php
This server-side module provides all required functionality to format and display cars in html

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/

class MovieZoneView {
	/*Class contructor: performs any initialization
	*/
	public function __construct() {
	}
	
	/*Class destructor: performs any deinitialiation
	*/		
	public function __destruct() {		
	}

    /*Creates top navigation panel
    */
    public function topNavPanel() {
        print file_get_contents('html/topnav.html');
    }

	/*Creates left navigation panel
	*/
	public function leftNavPanel() {
		include('html/leftnav.php');
	}
	
	/*Creates top navigation panel
	*/	
	public function filterBarPanel($actors, $directors, $genres, $classifications) {
		print "
		<div style='color: #0e5968; float:left;'>
			<div class='topnav'>
			<label for='actor'><b>Select actor:</b></label><br>
			<select name='actor' id='id_actor' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($actors as $actor) {
			print "<option value='".$actor['actor_name']."'>".$actor['actor_name']."</option>";
		}
		print "
			</select>
			</div>
			<div class='topnav'>
			<label for='director'><b>Select director:</b></label><br>
			<select name=\"director\" id='id_director' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>			
		";
		//------------------
		foreach ($directors as $director) {
			print "<option value='".$director['director_name']."'>".$director['director_name']."</option>";
		}	
		print "
			</select>
			</div>
			<div class='topnav'>
			<label for='genre'><b>Select genre:</b></label><br>
			<select name=\"genre\" id='id_genre' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
		//------------------
		foreach ($genres as $genre) {
			print "<option value='".$genre['genre_name']."'>".$genre['genre_name']."</option>";
		}
        print "
			</select>
			</div>
			<div class='topnav'>
			<label for='classification'><b>Select classification:</b></label><br>
			<select name=\"classification\" id='id_classification' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
        //------------------
        foreach ($classifications as $classification) {
            print "<option value='".$classification['classification']."'>".$classification['classification']."</option>";
        }
		print "
			</select>
			</div>
		</div>
		";
	}

    /*Creates actor filter panel
    */
    public function actorBarPanel($actors) {
        print "
		<div style='color: #0e5968; float:left;'>
			<div class='topnav'>
			<label for='actor'><h2>Select actor:</h2></label><br>
			<select name='actor' id='id_actor' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
        //------------------
        foreach ($actors as $actor) {
            print "<option value='".$actor['actor_name']."'>".$actor['actor_name']."</option>";
        }
        print "
			</select>
			</div>
		</div>	
		";
    }

    /*Creates director navigation panel
	*/
    public function directorBarPanel($directors) {
        print "
		<div style='color: #0e5968; float:left;'>
			<div class='topnav'>
			<label for='director'><h2>Select director:</h2></label><br>
			<select name=\"director\" id='id_director' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>			
		";
        //------------------
        foreach ($directors as $director) {
            print "<option value='".$director['director_name']."'>".$director['director_name']."</option>";
        }
        print "
			</select>
			</div>
		</div>
		";
    }

    /*Creates genres navigation panel
	*/
    public function genresBarPanel($genres) {
        print "
		<div style='color: #0e5968; float:left;'>
			<div class='topnav'>
			<label for='genre'><h2>Select genre:</h2></label><br>
			<select name=\"genre\" id='id_genre' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
        //------------------
        foreach ($genres as $genre) {
            print "<option value='".$genre['genre_name']."'>".$genre['genre_name']."</option>";
        }
        print "
			</select>
			</div>
		</div>
		";
    }

    /*Creates classifications navigation panel
	*/
    public function classificationsBarPanel($classifications) {
        print "
		<div style='color: #0e5968; float:left;'>
			<div class='topnav'>
			<label for='classification'><h2>Select classification:</h2></label><br>
			<select name=\"classification\" id='id_classification' onchange='movieFilterChanged();'>
				<option value='all'>Select all</option>
		";
        //------------------
        foreach ($classifications as $classification) {
            print "<option value='".$classification['classification']."'>".$classification['classification']."</option>";
        }
        print "
			</select>
			</div>
		</div>
		";
    }
	
	/*Displays error message
	*/
	public function showError($error) {
		print "<h2>Error: $error</h2>";
	}

    /*Displays an array of checkout movies
    */
    public function showCheckoutMovies($movie_array) {
        print "
        <h1>Checkout</h1>

        <p class='centre'>This module is currently being built and has not yet
            been completed<br>You have chosen the following movies to be booked/purchased:</p>
        ";
        if (!empty($movie_array)) {
            foreach ($movie_array as $movie) {
                $this->printCheckoutMovieInHtml($movie);
            }
        }
    }
	
	/*Displays an array of movies
	*/
	public function showMovies($movie_array) {
		if (!empty($movie_array)) {
			foreach ($movie_array as $movie) {
				$this->printMovieInHtml($movie);
			}
		}
	}
    
	/*Format a movie into html
	*/
	private function printMovieInHtml($movie) {
		//print_r($movie);
		
		if (empty($movie['thumbpath'])) {
			$thumbpath = _MOVIE_PHOTO_FOLDER_."default.jpg";
		} else {
			$thumbpath = _MOVIE_PHOTO_FOLDER_.$movie['thumbpath'];
		}
		$movie_id = $movie['movie_id'];
        $title = $movie['title'];
        $tagline = $movie['tagline'];
        $plot = $movie['plot'];
        $star1 = $movie['star1'];
        $star2 = $movie['star2'];
        $star3 = $movie['star3'];
        $costar1 = $movie['costar1'];
        $costar2 = $movie['costar2'];
        $costar3 = $movie['costar3'];
        $director = $movie['director'];
        $studio = $movie['studio'];
        $genre = $movie['genre'];
        $classification = $movie['classification'];
        $rental_period = $movie['rental_period'];
        $year = $movie['year'];
		$DVD_rental_price = $movie['DVD_rental_price'];
		$DVD_purchase_price = $movie['DVD_purchase_price'];
		$numDVD = $movie['numDVD'];
		$numDVDout = $movie['numDVDout'];
		$BluRay_rental_price = $movie['BluRay_rental_price'];
        $BluRay_purchase_price = $movie['BluRay_purchase_price'];
        $numBluRay = $movie['numBluRay'];
        $numBluRayOut = $movie['numBluRayOut'];
        $avaDVD = $numDVD-$numDVDout;
        $avaBluRay = $numBluRay- $numBluRayOut;

        if ($avaDVD > 0 && $avaBluRay > 0) {
            $movie_status = "Rent/Purchase";
        } elseif ($avaDVD > 0) {
            $movie_status = "Only DVD in Stock";
        } elseif ($avaBluRay > 0) {
            $movie_status = "Only BluRay in Stock";
        } else {
            $movie_status = "Currently Out of Stock";
        }
        if (!empty($_SESSION['checked_movies'])) {
            $checked_movies = $_SESSION['checked_movies'];
            if (isset($checked_movies[$movie_id]))
                $movie_status = "Already Selected";
        }

		print "
		<div class='car_card'>            
			<div class='title'>$title</div>			
			";

        if(!empty($_SESSION['member_id'])) {
            print "<form method=\"post\" enctype='multipart/form-data'>
                        <input type=\"hidden\" name=\"movie_id\" id=\"movie_id\" value='$movie_id'/>    
                        <input type='button' name=\"check\" id=\"check\" onclick='movieCheckClick(this.form);' value='$movie_status'/>
                    </form>";
        }

        print"
			<div class='photo_container'>
				<img src= '$thumbpath' alt='movie photo' class='photo'>
			</div>
			<div class='content'>
			    <b>$rental_period Rental</b><br>
				<b>Genre: </b>$genre<br>
				<b>Year: </b>$year<br>
				<b>Director: </b>$director<br>
				<b>Classification: </b>$classification<br>
				<b>Starring: </b>$star1, $star2, $star3, $costar1, $costar2 and $costar3<br>
				<b>Studio: </b>$studio<br>
				<b>Tagline: </b>$tagline<br>
				$plot<br>
				<b>Rental: </b>DVD - $DVD_rental_price BluRay - $BluRay_rental_price<br>
				<b>Purchase: </b>DVD - $DVD_purchase_price BluRay - $BluRay_purchase_price<br>
				<b>Availability: </b>DVD - $avaDVD BluRay - $avaBluRay<br>
		    </div>
		</div>
		";
	}

    /*Displays the left random new release movies
    */
    public function showLeftRandomNewRelease($movie_array) {
        if (!empty($movie_array)) {
            print "<div id = \"leftcol\">
                        <h2> New Releases</h2>
                    ";
            foreach ($movie_array as $movie) {
                $this->printRandomNewReleaseInHtml($movie);
            }
            print"</div>";
        }
    }

    /*Format a movie left new release div
    */
    private function printRandomNewReleaseInHtml($movie) {
        //print_r($movie);

        if (empty($movie['thumbpath'])) {
            $thumbpath = _MOVIE_PHOTO_FOLDER_."default.jpg";
        } else {
            $thumbpath = _MOVIE_PHOTO_FOLDER_.$movie['thumbpath'];
        }
        $title = $movie['title'];
        $tagline = $movie['tagline'];
        $plot = $movie['plot'];
        $star1 = $movie['star1'];
        $star2 = $movie['star2'];
        $star3 = $movie['star3'];
        $costar1 = $movie['costar1'];
        $costar2 = $movie['costar2'];
        $costar3 = $movie['costar3'];
        $director = $movie['director'];
        $genre = $movie['genre'];
        $classification = $movie['classification'];

        print "
		<fieldset>	
			<legend>$title</legend>
			<img class='moviePoster' src='$thumbpath' alt='Movie poster' height='150' width='105'>
			<span class='movieHeading'>Genre: </span>$genre<br>
			<span class='movieHeading'>Director: </span>$director<br>
			<span class='movieHeading'>Classification: </span>$classification<br>
			<span class='movieBold'>Starring: </span>$star1, $star2, $star3, $costar1, $costar2 and $costar3<br>
			<p><span class='movieBold'>$tagline</span></p>
			<p>$plot</p>
		</fieldset>
		<br><br>
		";
    }

    /*Format a movie for check out
    */
    private function printCheckoutMovieInHtml($movie) {
        //print_r($movie);

        if (empty($movie['thumbpath'])) {
            $thumbpath = _MOVIE_PHOTO_FOLDER_."default.jpg";
        } else {
            $thumbpath = _MOVIE_PHOTO_FOLDER_.$movie['thumbpath'];
        }
        $movie_id = $movie['movie_id'];
        $title = $movie['title'];
        $tagline = $movie['tagline'];
        $year = $movie['year'];
        $numDVD = $movie['numDVD'];
        $numDVDout = $movie['numDVDout'];
        $numBluRay = $movie['numBluRay'];
        $numBluRayOut = $movie['numBluRayOut'];
        $avaDVD = $numDVD-$numDVDout;
        $avaBluRay = $numBluRay- $numBluRayOut;

        print "
                <form class='checkoutForm'>
                <fieldset>
                    <legend>Movie<b>$movie_id</b> Information:</legend>
                    <img class='moviePoster' src='$thumbpath'  alt='Movie poster' height='150' width='105'>

                    <div>
                        <label for='title'>Title:</label>
                        <input type='text' name='title' size='45' value='$title' disabled>
                    </div>
                    <div>
                        <label for='year'>Year:</label>
                        <input type='text' name='year' size='4' value='$year' disabled>
                    </div>
                    <div>
                        <label for='tagline'>Tag line:</label>
                        <input type='text' name='tagline' size='60' value='$tagline' disabled>
                    </div>
                    <div>
                    <br><b>$avaDVD</b> DVD's are available and <b>$avaBluRay</b>  BluRay's are available<br></div>
                </fieldset>
                </form>
		";
    }


}
?>