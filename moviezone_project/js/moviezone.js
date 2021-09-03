/*Use onload event to load the page with random movies
*/
window.addEventListener("load", function(){
	makeAjaxGetRequest('moviezone_main.php', 'cmd_home', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide the left navigation panel
	document.getElementById('left_nav').style.display = "none";
	//show random new release panel
	document.getElementById('leftcol').style.display = "inherit";
});

/*Handles onchange event to filter the movie database
*/
function movieFilterChanged() {
	var actor = document.getElementById('id_actor');
	var director = document.getElementById('id_director');
	var genre = document.getElementById('id_genre');
	var classification = document.getElementById('id_classification');
	var params = '';
	if (actor!=null && actor.value != 'all')
		params += '&actor=' + actor.value;
	if (director!=null && director.value != 'all')
		params += '&director=' + director.value;
	if (genre!=null && genre.value != 'all')
		params += '&genre=' + genre.value;
	if (classification!=null && classification.value != 'all')
		params += '&classification=' + classification.value;
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_filter', params, updateContent);
}

/*Handles to show the techzone page
 */
function techzoneClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_techzone', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide the left navigation panel
	document.getElementById('left_nav').style.display = "none";
	//show random new release panel
	document.getElementById('leftcol').style.display = "inherit";
}

/*Handles to show the contact page
 */
function contactClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_contact', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide the left navigation panel
	document.getElementById('left_nav').style.display = "none";
	//show random new release panel
	document.getElementById('leftcol').style.display = "inherit";
}

/*Handles to show the home page
 */
function homeClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_home', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide the left navigation panel
	document.getElementById('left_nav').style.display = "none";
	//show random new release panel
	document.getElementById('leftcol').style.display = "inherit";
}

/*Handles to show the home page
 */
function joinClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_join', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide the left navigation panel
	document.getElementById('left_nav').style.display = "none";
	//show random new release panel
	document.getElementById('leftcol').style.display = "inherit";

	//load a js file to validate form
	var form_validation = document.createElement("script");
	form_validation.type = "text/javascript";
	form_validation.src = "js/form_validation.js";
	document.head.appendChild(form_validation);

	//load a js file to listen form event
	var register_form_events = document.createElement("script");
	register_form_events.type = "text/javascript";
	register_form_events.src = "js/register_form_events.js";
	document.body.appendChild(register_form_events);
}

/*Handles to show movie zone page
 */
function moviezoneClick() {
	makeAjaxGetRequest('moviezone_main.php', 'cmd_movie_select_random', null, updateContent);
	//show the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
	//show the left navigation panel
	document.getElementById('left_nav').style.display = "inherit";
}

/*Handles show all movies onlick event to show all movies
*/
function movieShowAllClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_movie_select_all', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}

/*Handles show all movies onlick event to show new release movies
 */
function movieNewReleaseClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_movie_new_release', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}

/*Handles filter movies onclick event to filter movies
*/
function movieFilterClick() {
	//load the navigation panel on demand
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_top_nav', null, updateTopNav);
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}
/*Handles filter movies onclick event to filter movies
 */
function movieActorFilterClick() {
	//load the navigation panel on demand
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_actor_nav', null, updateTopNav);
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}
/*Handles filter movies onclick event to filter movies
 */
function movieDirectorFilterClick() {
	//load the navigation panel on demand
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_director_nav', null, updateTopNav);
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}
/*Handles filter movies onclick event to filter movies
 */
function movieGenreFilterClick() {
	//load the navigation panel on demand
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_genre_nav', null, updateTopNav);
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}
/*Handles filter movies onclick event to filter movies
 */
function movieClassificationFilterClick() {
	//load the navigation panel on demand
	makeAjaxGetRequest('moviezone_main.php', 'cmd_show_classification_nav', null, updateTopNav);
	//hide random new release panel
	document.getElementById('leftcol').style.display = "none";
}

/*Handles show members login form
 */
function memberLoginFormClick() {
	makeAjaxGetRequest('moviezone_main.php','cmd_user_login_form', null, updateContent);
	//hide the top navigation panel
	document.getElementById('id_topnav').style.display = "none";
	//hide random new release panel
	document.getElementById('leftcol').style.display = "inherit";
	//show the left navigation panel
	document.getElementById('left_nav').style.display = "none";
}

/*Updates the content area if success
*/
function updateContent(data) {
	document.getElementById('id_content').innerHTML = data;
}
/*Updates the top navigation panel
*/
function updateTopNav (data) {
	var topnav = document.getElementById('id_topnav');
	topnav.innerHTML = data;
	topnav.style.display = "inherit";
}

/*Handles onclick events to filter/add/edit/delete movies
 */

//submit member data to server
function btnAddMemberSubmitClick() {
	var memberdata = new FormData(document.joinform);
	makeAjaxPostRequest('moviezone_main.php', 'cmd_member_add', memberdata, function(data) {
		alert("Successfully submit the detail.");
		document.joinform.reset(); //reset form
	});


}

//send ajax request to ask for server-side authentication
function userlogin_btnClicked() {
	var loginformData = new FormData(document.login);
	makeAjaxPostRequest('moviezone_main.php','cmd_user_login',loginformData, function(data) {
		if(data=="_OK_") {
			alert("Successfully log in.");
			window.location.reload();
		}
	});
}

//user logout button
function euserlogout_btnClicked() {
	makeAjaxGetRequest('moviezone_main.php','cmd_user_logout', null, function(data) {
		alert(data);
		window.location.replace('index.php');
	});
}

//handles the movie checkbox click event to sends request to server to check/uncheck movie
function movieCheckClick(formdata) {
	var movieid = formdata.movie_id.value;
	var params = "&movie_id=" + movieid;
	makeAjaxGetRequest('moviezone_main.php','cmd_movie_check', params, function(data) {
		movieShowAllClick();
	});
	/*event.stopPropagation(); //so edit event does not fire.*/
}

//user checkout button
function usercheckout_btnClicked() {
	makeAjaxGetRequest('moviezone_main.php','cmd_user_checkout', null, function(data) {
		updateContent(data);
	});

	//hide random new release panel
	document.getElementById('leftcol').style.display = "inherit";
	//show the left navigation panel
	document.getElementById('left_nav').style.display = "none";
}