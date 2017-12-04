// Global/widely used functions
// Note; this file is included on all pages, so use document.ready or similar with caution!

$(document).ready(function(){
    //load the footer and nav bar
    var header = "<div class='header-bar'><a href='/landing'>OCN Portal</a></div>";
    var footer = "<div class='footer-bar'>&copy; 2017 Concordia University</div>";
    $('body').prepend(header);
    $('body').append(footer);
});
function getUrlParameter(name){	
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == name)
        {
            return sParameterName[1];
        }
    }
	return false;
}

// Cookies
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";               

    document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}


//Redirect a non-logged in user to the login page
function redirectOut(){
	alert("You must be logged in to access this site.");
	window.location.href = "/index.html";
}