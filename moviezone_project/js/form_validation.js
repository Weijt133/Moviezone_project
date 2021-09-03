/**
 * Created by Administrator on 2018/9/15.
 */


//Event handlers
function contactChange(event){
    var mobileMarker = document.getElementById("mobilemarker");
    var phoneMarker = document.getElementById("phonemarker");
    var emailMarker = document.getElementById("emailmarker");

    //Remove old markers
    mobileMarker.style.visibility = "hidden";
    phoneMarker.style.visibility = "hidden";
    emailMarker.style.visibility = "hidden";

    //Show marker in right elements
    switch (event.currentTarget.value) {
        case "mobile":
            mobileMarker.style.visibility = "visible";
            break;
        case "landline":
            phoneMarker.style.visibility = "visible";
            break;
        case "email":
            emailMarker.style.visibility = "visible";
            break;
    }
}


function magazineChange(event){
    var streetMarker = document.getElementById("streetmarker");
    var suburbMarker = document.getElementById("suburbmarker");
    var postcodeMarker = document.getElementById("postcodemarker");

    if(event.currentTarget.checked == true){
        //Mark address elements as compulsory

        //Add markers to all the address elements.
        streetMarker.style.visibility = "visible";
        suburbMarker.style.visibility = "visible";
        postcodeMarker.style.visibility = "visible";
    }else {
        //Unmark address elements as compulsory
        streetMarker.style.visibility = "hidden";
        suburbMarker.style.visibility = "hidden";
        postcodeMarker.style.visibility = "hidden";

        //clear any hilights
        clearHilight("streetrow");
        clearHilight("suburbrow");
        clearHilight("postcoderow");
    }

}



function displayPopup(element, event){
    var popupWindow = document.getElementById(element);
    popupWindow.style.display = "block";
    popupWindow.style.position = "absolute";

    popupWindow.style.top = event.pageY + 14 + "px";
    popupWindow.style.left = event.pageX + 14 + "px";
}

function hidePopup(element){
    document.getElementById(element).style.display = "none";
}

function helpPopup(event){

    switch(event.currentTarget.id){
        case "preferredcontact" :
            displayPopup("contacthelp", event);
            break;
        case "mobilenum" :
            displayPopup("mobilehelp", event);
            break;
        case "phonenum" :
            displayPopup("phonehelp", event);
            break;
        case "email" :
            displayPopup("emailhelp", event);
            break;
        case "streetaddr" :
            displayPopup("streethelp", event);
            break;
        case "suburbstate" :
            displayPopup("suburbhelp", event);
            break;
        case "postcode" :
            displayPopup("postcodehelp", event);
            break;
        case "joinusername" :
            displayPopup("usernamehelp", event);
            break;
        case "userpass" :
            displayPopup("passwordhelp", event);
            break;
        case "verifypass" :
            displayPopup("verifypasshelp", event);
            break;
    }
}

function helpPopdown(event){
    switch(event.currentTarget.id){
        case "preferredcontact" :
            hidePopup("contacthelp");
            break;
        case "mobilenum" :
            hidePopup("mobilehelp");
            break;
        case "phonenum" :
            hidePopup("phonehelp");
            break;
        case "email" :
            hidePopup("emailhelp");
            break;
        case "streetaddr" :
            hidePopup("streethelp");
            break;
        case "suburbstate" :
            hidePopup("suburbhelp");
            break;
        case "postcode" :
            hidePopup("postcodehelp");
            break;
        case "joinusername" :
            hidePopup("usernamehelp");
            break;
        case "userpass" :
            hidePopup("passwordhelp");
            break;
        case "verifypass" :
            hidePopup("verifypasshelp");
            break;
    }
}
