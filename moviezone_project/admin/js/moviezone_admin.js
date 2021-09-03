/*Use onload event to load the page with random movies
*/

window.addEventListener("load", function() {
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_select_all', null, updateContent);
});

/*Updates the top navigation panel
 */
function updateTopNav (data) {
	var topnav = document.getElementById('id_topnav');
	topnav.innerHTML = data;
	topnav.style.display = "inherit";
}

/*Updates the content area if success
*/
function updateContent(data) {
	document.getElementById('id_content').innerHTML = data;
}

/*Handles onclick events to filter/add/edit/delete movies
*/

//submit movie data to server
function btnAddEditMovieSubmitClick(command) {
	var moviedata = new FormData(document.movieaddform);
	makeAjaxPostRequest('moviezone_admin_main.php', command, moviedata, function(data) {
		if (data == '_OK_') {
			if (command == 'cmd_movie_add') {
				alert('The movie data has been successfully updated to the database');
				document.movieaddform.reset(); //reset form
				document.getElementById('id_photo_frame').src = '';
				document.getElementById('id_error').innerHTML = '';
			} else {
				btnAddEditMovieExitClick();
			}
		} else {
			alert(data);
			document.getElementById('id_error').innerHTML = data;
		}
	});
}

//exit add/edit mode and return to browsing mode
function btnAddEditMovieExitClick() {
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_select_all', null, function(data) {
		updateContent(data);
		//show the top navigation panel
		makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_show_top_nav', null, function(data) {
		});
	});	
}

//shows movie add/edit form
function addMovieClick() {
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_add_form', null, function(data) {
		updateContent(data); //load the add/edit form to the content area
		updateMovieForm(); //populate the add/edit form
	});
}

//sends request to server to 
function editMovieClick(movie_id) {
	var params = '&movie_id=' + movie_id;
	makeAjaxGetRequest('moviezone_admin_main.php','cmd__edit_form', params, function(data) {
		updateContent(data); //load the add/edit form to the content area		
		updateMovieForm(movie_id); //populate the add/edit form
	});
}

//sends request to server to display delete movie UI
function deleteMovieClick() {
	if (confirm("Are you sure to delete selected movies?") == true) {
		makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_delete', null, function(data){
			if (data == '_OK_') {
				makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_select_all', null, updateContent);
			} else {
				updateContent(data);
			}
		});
	}
}

//exit to the main app
function exitClick() {
	if (confirm("Are you sure to exit?") == false)
		return;
	//load the navigation panel on demand
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_admin_logout', null, function(data) {
		if (data == '_OK_') {
			window.location.replace('../index.php');
		}		
	});
}

//handles the movie checkbox click event to sends request to server to check/uncheck movie
function movieCheckClick(check) {
	var params = '&movie_id=' + check.value;
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_check', params, function(data) {
		if (data == '_OK_') {		
			check.checked = true;
		} else
			check.checked = false;
	});
	event.stopPropagation(); //so edit event does not fire.
}

/*Loads photo from local system to input form
*/
function loadPhoto(fileSelector) {
	var files = fileSelector.files;
    // FileReader support
    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            document.getElementById('id_photo_frame').src = fr.result;
        }
        fr.readAsDataURL(files[0]);
    }

    // Not supported
    else {
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
    }
}

/*Gets the all of options for the drop-down list from server and create the options on the fly
*/
function loadOptionMovieForm(){
	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_rentalperiod_select_all', null, function(data) {
		try {
			var rental_periods = JSON.parse(data);
			for (var i = 0; i < rental_periods.length; i++) {
				var rental_period = rental_periods[i];
				var option = document.createElement("option");
				option.text = rental_period.rental_period;
				option.value = rental_period.rental_period;
				document.movieaddform.rental_period.add(option); //rental_period dropbox in movie form
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	});

	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_director_select_all', null, function(data) {
		try {
			var directors = JSON.parse(data);
			for (var i = 0; i < directors.length; i++) {
				var director = directors[i];
				var option = document.createElement("option");
				option.text = director.director_name;
				option.value = director.director_id;
				document.movieaddform.director.add(option); //rental_period dropbox in movie form
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	});

	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_studio_select_all', null, function(data) {
		try {
			var studios = JSON.parse(data);
			for (var i = 0; i < studios.length; i++) {
				var studio = studios[i];
				var option = document.createElement("option");
				option.text = studio.studio_name;
				option.value = studio.studio_id;
				document.movieaddform.studio.add(option); //rental_period dropbox in movie form
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	});
	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_genre_select_all', null, function(data) {
		try {
			var genres = JSON.parse(data);
			for (var i = 0; i < genres.length; i++) {
				var genre = genres[i];
				var option = document.createElement("option");
				option.text = genre.genre_name;
				option.value = genre.genre_id;
				document.movieaddform.genre.add(option); //rental_period dropbox in movie form
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	});
	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_classification_select_all', null, function(data) {
		try {
			var classifications = JSON.parse(data);
			for (var i = 0; i < classifications.length; i++) {
				var classification = classifications[i];
				var option = document.createElement("option");
				option.text = classification.classification;
				option.value = classification.classification;
				document.movieaddform.classification.add(option); //rental_period dropbox in movie form
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	});
	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_actor_select_all', null, function(data) {
		try {
			var actors = JSON.parse(data);
			for (var i = 0; i < actors.length; i++) {
				var actor = actors[i];
				var option = document.createElement("option");
				option.text = actor.actor_name;
				option.value = actor.actor_id;
				document.movieaddform.star1.add(option); //actor dropbox in movie form
			}
			for (var i = 0; i < actors.length; i++) {
				var actor = actors[i];
				var option = document.createElement("option");
				option.text = actor.actor_name;
				option.value = actor.actor_id;
				document.movieaddform.star2.add(option); //actor dropbox in movie form
			}
			for (var i = 0; i < actors.length; i++) {
				var actor = actors[i];
				var option = document.createElement("option");
				option.text = actor.actor_name;
				option.value = actor.actor_id;
				document.movieaddform.star3.add(option); //actor dropbox in movie form
			}
			for (var i = 0; i < actors.length; i++) {
				var actor = actors[i];
				var option = document.createElement("option");
				option.text = actor.actor_name;
				option.value = actor.actor_id;
				document.movieaddform.costar1.add(option); //actor dropbox in movie form
			}
			for (var i = 0; i < actors.length; i++) {
				var actor = actors[i];
				var option = document.createElement("option");
				option.text = actor.actor_name;
				option.value = actor.actor_id;
				document.movieaddform.costar2.add(option); //actor dropbox in movie form
			}
			for (var i = 0; i < actors.length; i++) {
				var actor = actors[i];
				var option = document.createElement("option");
				option.text = actor.actor_name;
				option.value = actor.actor_id;
				document.movieaddform.costar3.add(option); //actor dropbox in movie form
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	});
}

function updateMovieForm(movie_id) {
	//add movie mode
		try {

			loadOptionMovieForm();//load all of options for the drop-down list from server
			
			if (movie_id != null) { //update movie mode
				//update the form with movie data
				var params = '&movie_id=' + movie_id;
				makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_movie_select_by_id', params,
				function(data) {
					var moviedata = JSON.parse(data);
					document.movieaddform.movie_id.value = moviedata[0].movie_id;
					document.movieaddform.thumbpath.value = moviedata[0].thumbpath;
					document.movieaddform.title.value = moviedata[0].title;
					document.movieaddform.year.value = moviedata[0].year;
					document.movieaddform.tagline.value = moviedata[0].tagline;
					document.movieaddform.plot.value = moviedata[0].plot;
					document.movieaddform.director.text = moviedata[0].director;
					document.movieaddform.studio.text = moviedata[0].studio;
					document.movieaddform.genre.text = moviedata[0].genre;
					document.movieaddform.classification.text = moviedata[0].classification;
					document.movieaddform.star1.text = moviedata[0].star1;
					document.movieaddform.star2.text = moviedata[0].star2;
					document.movieaddform.star3.text = moviedata[0].star3;
					document.movieaddform.costar1.text = moviedata[0].costar1;
					document.movieaddform.costar2.text = moviedata[0].costar2;
					document.movieaddform.costar3.text = moviedata[0].costar3;
					document.movieaddform.rental_period.text = moviedata[0].rental_period;
					document.movieaddform.DVD_rental_price.value = moviedata[0].DVD_rental_price;
					document.movieaddform.DVD_purchase_price.value = moviedata[0].DVD_purchase_price;
					document.movieaddform.numDVD.value = moviedata[0].numDVD;
					document.movieaddform.numDVDout.value = moviedata[0].numDVDout;
					document.movieaddform.BluRay_rental_price.value = moviedata[0].BluRay_rental_price;
					document.movieaddform.BluRay_purchase_price.value = moviedata[0].BluRay_purchase_price;
					document.movieaddform.numBluRay.value = moviedata[0].numBluRay;
					document.movieaddform.numBluRayOut.value = moviedata[0].numBluRayOut;

					/*for(var i=0; i<document.movie.state.options.length; i++) {
						if (document.movie.state.options[i].text == moviedata[0].genre ) {
							document.movie.state.selectedIndex = i;
							break;
						}
					}*/
					document.getElementById('id_photo_frame').src = '../photos/' + moviedata[0].thumbpath;
					document.movieaddform.btnSubmit.onclick = function() {
						btnAddEditMovieSubmitClick('cmd_movie_edit');
					}
				});		
			} else {
				document.movieaddform.btnSubmit.onclick = function() {
					btnAddEditMovieSubmitClick('cmd_movie_add');
				}
			}
		} catch (ex) {
			//error, simply display data
			document.getElementById('id_error').innerHTML = data;
		}
	//assign event handlers to other components of the form
	/*Important note: the following is a common mistake ->
		document.movie.btnExit.onclick = btnAddEditMovieExitClick(); //this will invoke the function and
		assign the result to onlick instead of assigning the function to onlick!
	*/		
	document.movieaddform.btnExit.onclick = function() { //this is a correct way of doing the assignment
		btnAddEditMovieExitClick();
	}
	/*//another way of assigning functions to events but this does not work for IE9 and below
	document.movie.make.addEventListener('keyup', function(){
		makeKeyupHandler(this) //this represents the event owner i.e. the make input in this case
	});
	//
	document.movie.body.addEventListener('keyup', function(){
		bodyKeyupHandler(this) //this represents the event owner i.e. the body input in this case
	});*/
	
}

/*Handles make input keyup event and contact with server via Ajax to ger the list of makes*/
function makeKeyupHandler(make) {
	var params = "&keyword=" + make.value;
	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_make_filter', params, function(data) {
		document.getElementById('id_make_filter').innerHTML = data;
	});
}

/*Handle make filter onlick event and update make with the value and hide the make filter*/
function makeInputUpdate(value) {
	document.movieaddform.make.value = value;
	document.getElementById('id_make_filter').innerHTML = '';
}

/*Handles make input keyup event and contact with server via Ajax to ger the list of makes*/
function bodyKeyupHandler(body) {
	var params = "&keyword=" + body.value;
	makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_body_filter', params, function(data) {
		document.getElementById('id_body_filter').innerHTML = data;
	});
}

/*Handle make filter onlick event and update make with the value and hide the make filter*/
function bodyInputUpdate(value) {
	document.movieaddform.body.value = value;
	document.getElementById('id_body_filter').innerHTML = '';
}

/*Handles show all movies while onlick event
 */
function movieShowPanelAllClick() {
    makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_show_all_panel', null, updateContent);
}

/*Handles to load the movie edit form
 */
function loadMovieEditForm(movie_id) {
	var params = "&movie_id=" + movie_id;
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_load_movie_form', params, updateContent);
}

function updateMovieEditForm() {
	var movie_id = document.getElementById('movie_id').value;
	//add movie mode
	try {

		loadMovieEditForm(movie_id);//load all of options for the drop-down list from server

		if (movie_id != null) { //update movie mode
			var params = "&movie_id=" + movie_id;
			makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_movie_select_by_id', params,
				function(data) {
					var moviedata = JSON.parse(data);
					document.movieeditform.movie_id.value = moviedata[0].movie_id;
					document.movieeditform.movie1_id.value = moviedata[0].movie_id;
					document.movieeditform.title.value = moviedata[0].title;
					document.movieeditform.title1.value = moviedata[0].title;
					document.movieeditform.thumbpath.src = "../photos/"+moviedata[0].thumbpath;
					document.movieeditform.year.value = moviedata[0].year;
					document.movieeditform.tagline.value = moviedata[0].tagline;
					document.movieeditform.rental_period.value = moviedata[0].rental_period;
					document.movieeditform.dvdrental.value = moviedata[0].DVD_rental_price;
					document.movieeditform.dvdpurchase.value = moviedata[0].DVD_purchase_price;
					document.movieeditform.dvdstock.value = moviedata[0].numDVD;
					document.movieeditform.dvdrented.value = moviedata[0].numDVDout;
					document.movieeditform.dvdself.value = moviedata[0].numDVD-moviedata[0].numDVDout;
					document.movieeditform.blurental.value = moviedata[0].BluRay_rental_price;
					document.movieeditform.blupurchase.value = moviedata[0].BluRay_purchase_price;
					document.movieeditform.blustock.value = moviedata[0].numBluRay;
					document.movieeditform.blurented.value = moviedata[0].numBluRayOut;
					document.movieeditform.bluself.value = moviedata[0].numBluRay-moviedata[0].numBluRayOut;
				});
		}
	} catch (ex) {
		//error, simply display data
		alert(data);
	}
}

//update movie data to server
function UpdateMovieSubmitClick() {
	var moviedata = new FormData(document.movieeditform);

	makeAjaxPostRequest('moviezone_admin_main.php', 'cmd_movie_edit', moviedata, function(data) {
		if (data == '_OK_') {
			alert('The movie data has been successfully updated to the database');
			movieShowPanelAllClick();
		} else {
			alert(data);
			document.getElementById('id_error').innerHTML = data;

		}
	});
}

//sends request to server to display delete movie UI
function deleteMovieClick() {
	if (confirm("Are you sure to delete this movie?") == true) {
		var movieid = document.movieeditform.movie_id.value;
		var params = "&movie_id=" + movieid;
		makeAjaxGetRequest('moviezone_admin_main.php','cmd_movie_delete', params, function(data){
			if (data == '_OK_') {
				alert('Successfully delete the movie.');
				movieShowPanelAllClick();
			} else {
				alert(data);
				updateContent(data);
			}
		});
	}
}

/*Handles show all members while onlick event
 */
function memberShowAllClick() {
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_member_show_all', null, updateContent);
}

/*Handles to load the member edit form
 */
function loadMemberEditForm(member_id) {
	var params = "&member_id=" + member_id;
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_load_member_form', params, updateContent);
}

function updateMemberEditForm() {
	var member_id = document.getElementById('user_id').value;
	//add member mode
	try {

		loadMemberEditForm(member_id);//load all of options for the drop-down list from server

		if (member_id != null) { //update movie mode
			var params = "&member_id=" + member_id;
			makeAjaxGetRequest('moviezone_admin_main.php', 'cmd_member_select_by_id', params,
				function(data) {
					var memberdata = JSON.parse(data);
					document.joineditform.updateid.value = memberdata[0].member_id;
					document.joineditform.surname.value = memberdata[0].surname;
					document.joineditform.othername.value = memberdata[0].other_name;
					document.joineditform.joinusername.value = memberdata[0].username;
					document.joineditform.userpass.value = memberdata[0].password;
					document.joineditform.occupation.value = memberdata[0].occupation;
					document.joineditform.joindate.value = memberdata[0].join_date;
					document.joineditform.contactmethod.text = memberdata[0].contact_method;
					document.joineditform.email.value = memberdata[0].email;
					document.joineditform.mobilenum.value = memberdata[0].mobile;
					document.joineditform.phonenum.value = memberdata[0].landline;
					if(memberdata[0].magazine == 1)
                        document.joineditform.magazine.checked = true;
                    else
                        document.joineditform.magazine.checked = false;
					document.joineditform.streetaddr.value = memberdata[0].street;
					document.joineditform.suburbstate.value = memberdata[0].suburb;
					document.joineditform.postcode.value = memberdata[0].postcode;

					document.joindeleteform.deleteid.value = memberdata[0].member_id;
					document.joindeleteform.user.value = memberdata[0].other_name+", "+memberdata[0].surname+"Username: "+memberdata[0].username;

				});
		}
	} catch (ex) {
		//error, simply display data
		document.getElementById('id_error').innerHTML = data;
	}
}

//update member data to server
function UpdateMemberSubmitClick() {
	var memberdata = new FormData(document.joineditform);
	if(document.joineditform.magazine.checked)
		memberdata['magazine']=1;
	else
		memberdata['magazine']=0;
	makeAjaxPostRequest('moviezone_admin_main.php', 'cmd_member_edit', memberdata, function(data) {
		if (data == '_OK_') {
			alert('The movie data has been successfully updated to the database');
			memberShowAllClick();
		} else {
			alert(data);
			document.getElementById('id_error').innerHTML = data;

		}
	});
}

//sends request to server to display delete member UI
function deleteMemberClick() {
		if (confirm("Are you sure to delete this member?") == true) {
			var memberid = document.joindeleteform.deleteid.value;
			var params = "&member_id=" + memberid;
			makeAjaxGetRequest('moviezone_admin_main.php','cmd_member_delete', params, function(data){
				if (data == '_OK_') {
					alert('Successfully delete the member.');
					memberShowAllClick();
				} else {
					alert(data);
					updateContent(data);
				}
			});
		}
}

/*Handles to show the home page
 */
function createMemberClick() {
	makeAjaxGetRequest('moviezone_admin_main.php','cmd_member_createtable', null, updateContent);

}

//submit member data to server
function btnAddMemberSubmitClick() {
	var memberdata = new FormData(document.joinform);
	makeAjaxPostRequest('moviezone_admin_main.php', 'cmd_member_add', memberdata, function(data) {
		alert("Successfully submit member detail.");
		document.joinform.reset(); //reset form
	});


}