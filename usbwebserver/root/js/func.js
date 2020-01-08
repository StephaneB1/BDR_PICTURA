/*
 * Summary: Displays and hide the tabs
 */
function displayTab(tabName, selector) {
    //Hide all tabs
    var tabs = document.getElementsByClassName("tab-content");
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].style.display = "none";
    }
    //Desactive selectors
    var selectors = document.getElementsByClassName("tab-selector");
    for (var i = 0; i < selectors.length; i++) {
        selectors[i].className = selectors[i].className.replace(" tab-selected", "");
    }

    //Display current tab
    var currentTabContent = document.getElementById(tabName);
    if(currentTabContent != null) {
        currentTabContent.style.display = "block";
    }
    //Active selector
    selector.className += " tab-selected";
}


/*
* Summary: Displays and hide the elements from their id
*/
function displayId(idToShow, idToHide) {
    
	var divToShow = document.getElementById(idToShow);
	var divToHide = document.getElementById(idToHide);

	//Display div
    if(divToShow != null) {
        divToShow.style.display = "block";
    }

    //Hide div
    if(divToHide != null) {
        divToHide.style.display = "none";
    }
}

/*
* Summary: Displays and hide the elements from their class
*/
function displayClass(classToShow, classToHide) {
	var divsToShow = document.getElementsByClassName(classToShow);
	var divsToHide = document.getElementsByClassName(classToHide);

	//Display divs
    if(divsToShow != null) {
        for(var i = 0; i < divsToShow.length; i++) {
            divsToShow[i].style.display = "block";
        }
    }

    if(divsToHide != null) {
        //Hide divs
        for(var i = 0; i < divsToHide.length; i++) {
            divsToHide[i].style.display = "none";
        }
	}
}


/*
* Summary:
*/
function slideById(clicker, divId, inOut, ms) {
	if(inOut == true) {
		$("#"+divId).slideDown(ms);
		inOut = !inOut;
		clicker.setAttribute("onclick", "slideById(this, '" + divId + "', " + inOut + ", " + ms + ")");
	} else {
		$("#"+divId).slideUp(ms);

		inOut = !inOut;
		clicker.setAttribute("onclick", "slideById(this, '" + divId + "', " + inOut + ", " + ms + ")");
	}
}

var loadFile = function(event, id) {
	var image = document.getElementById(id);
	image.src = URL.createObjectURL(event.target.files[0]);
};

function likePicture(username, photoId) {
    alert(username + " " + photoId);
    /*$.ajax({
      url:"test.php", //the page containing php script
      type: "POST", //request type
      success:function(result){
       alert(result);
     }
   });*/
}

var on = false;
function toggleComments() {
    var commentIcon = document.getElementById("commentsButton");
    var commentContainer = document.getElementById("commentsContainer");
    on = !on;

    commentIcon.style.backgroundImage = on ? "url('../imgs/comments_on.png')" : "url('../imgs/comments_off.png')";
    commentContainer.style.display    = on ? "block" : "none";
}