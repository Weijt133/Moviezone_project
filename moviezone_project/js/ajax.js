/*Makes an Ajax GET request to the request_handler and passess the request_id to
the handler through 'request' parameter. Example of using this function:
makeAjaxGetRequest('moviezone.php', 'cmd_movie_select', '&key=value', success);
success - is a callback function which is called when the request has been successful
*/
function makeAjaxGetRequest(request_handler, requestid, params, success) {
	var xhttp;    

	xhttp = new XMLHttpRequest();
	if (params == null)	{
		xhttp.open("GET", request_handler + "?request=" + requestid, true);
	} else {
		xhttp.open("GET", request_handler + "?request=" + requestid + params, true);
	}
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (success == null)
				return this.responseText;
			else
				success(this.responseText);
		}
	};		
	xhttp.send();			
	
}

/*Makes an Ajax POST request to the request_handler and passess the request_id to
the handler through 'request' parameter. Example of using this function:
makeAjaxPostRequest('moviezone.php', 'cmd_movie_select', '&key=value', success);
success - is a callback function which is called when the request has been successful
*/
function makeAjaxPostRequest(request_handler, requestid, params, success) {
	var xhttp;    

	xhttp = new XMLHttpRequest();
	xhttp.open("POST", request_handler, true);	
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (success == null)
				return this.responseText;
			else
				success(this.responseText);
		}
	};
	if (params == null) {
		xhttp.send("request=" + requestid);		
	} else {		
		if (params instanceof FormData) {
			//params is an instance of formdata	then append the requestid to it before sending out			
			params.append('request', requestid);
			xhttp.send(params);
		} else {
			//xhttp.setRequestHeader("Content-type", "multipart/form-data"); //for sending binary
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//simple string params
			xhttp.send("request=" + requestid + params);
		}
		
	}
}
