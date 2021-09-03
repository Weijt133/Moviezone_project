<?php
/*-------------------------------------------------------------------------------------------------
@Module: moviezone_admin_view.php
This server-side module provides all required functionality to format and display cars in html

@Author: Vinh Bui (vinh.bui@scu.edu.au)
@Modified by: 
@Date: 09/09/2017
--------------------------------------------------------------------------------------------------*/
require_once('moviezone_admin_config.php');

class MovieZoneAdminView {
	/*Class contructor: performs any initialization
	*/
	public function __construct() {		
	}
	
	/*Class destructor: performs any deinitialiation
	*/		
	public function __destruct() {		
	}
	
	/*Creates left navigation panel
	*/
	public function leftNavPanel() {
		print file_get_contents('html/leftnav.html');
	}

	
	/*Displays error message
	*/
	public function showError($error) {
		print "<h2 style='color: red'>Error: $error</h2>";
	}
	
	/*Displays an array of cars
	*/
	public function showMovies($movie_array) {
		if (!empty($movie_array)) {
			foreach ($movie_array as $movie) {
				$this->printMovieInHtml($movie);
			}
		}
	}

    /*Creates member search panel
    */
    public function searchMemberPanel($members) {
        print "
		<span style = \"float:left; color:red; font-weight:bold;\">".$_SESSION['authorised']."<br>Admin-mode</span><br><h1>Edit/Delete member</h1><h4>Select User to Edit/Delete</h4>
            <p>Users shown in dropdown as:<br>
                Surname, Other names - Username</p>
            <form method='post' enctype='multipart/form-data'>
                <input type = 'hidden' name='action' value='edit_member' />
                <select name='user_id' id='user_id'>
		";
        //------------------
        foreach ($members as $member) {
            print "<option value='".$member['member_id']."'>".$member['surname'].", ".$member['other_name']." - ".$member['username']."</option>";
        }
        print "
			    </select>
                <input type='button' value='Search' onclick='updateMemberEditForm();' />
            </form>
				
		";
    }

    /*Creates member search panel
   */
    public function searchMoviePanel($movies) {
        print "
		<span style = \"float:left; color:red; font-weight:bold;\">".$_SESSION['authorised']."<br>Admin-mode</span><br><h1>Edit/Delete movie</h1><h4>Select Movie to Edit/Delete</h4>
            <p>Movies shown in dropdown as:<br>
                Title - Year</p>
            <form method='post' enctype='multipart/form-data'>
                <input type = 'hidden' name='action' value='edit_member' />
                <select name='movie_id' id='movie_id'>
		";
        //------------------
        foreach ($movies as $movie) {
            print "<option value='".$movie['movie_id']."'>".$movie['title']." - ".$movie['year']."</option>";
        }
        print "
			    </select>
                <input type='button' value='Search' onclick='updateMovieEditForm();' />
            </form>
				
		";
    }
	
	/*Format a movie into html
	*/
	private function printMovieInHtml($movie) {
		//
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
		//
		$checked = ''; //check the movie checkbox if the movie is previously selected
		if (!empty($_SESSION['checked_cars'])) {
			$checked_cars = $_SESSION['checked_cars'];
			if (isset($checked_cars[$movie_id]))
				$checked = 'checked';
		}
		print "
		<div class='car_card' onclick='editMovieClick($movie_id);'>	
			<div class='title'>				
				<input type='checkbox' id='id_check' value='$movie_id' onclick='movieCheckClick(this);' $checked>
				$title
			</div>
			<div class='photo_container'>
				<img src= '$thumbpath' alt='movie thumbpath' class='thumbpath'>
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
	
	/*Shows make filter list */
	public function showMakeFilterList($makes) {
		print "<div class='filter-content'>";
		foreach ($makes as $make) {
			print "<a onclick='makeInputUpdate(this.text);'>".$make['name']."</a>";
		}
		print "</div>";
	}
	
	/*Shows make filter list */
	public function showBodyFilterList($bodies) {
		print "<div class='filter-content'>";
		foreach ($bodies as $body) {
			print "<a onclick='bodyInputUpdate(this.text);'>".$body['name']."</a>";
		}
		print "</div>";
	}
	
}
?>