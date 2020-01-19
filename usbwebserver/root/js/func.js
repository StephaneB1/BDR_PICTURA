/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        19.01.2019
 * 
 * Summary:     Main class for javascript functions
 */

function init() {

    // Enter on search bar
    const node = document.getElementById("searchBar");
    node.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            location.href='../index.php?s=' + node.value;
        }
    });
}

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
 * Summary: Enables / disables night mode
 */
var nightModeOn = false;
function switchNightMode() {
    nightModeOn = !nightModeOn;
    document.getElementById("nightmodeButton").style.backgroundImage = nightModeOn ? "url(../imgs/nightmodeButtonEnabled.png)" : "url(../imgs/nightmodeButton.png)";

    document.getElementById("main_container").classList.toggle("nightmode");
    document.getElementById("topSearchPanel").classList.toggle("nightmode");
    document.getElementById("community_panel").classList.toggle("nightmode");
    document.getElementById("middle_panel").classList.toggle("nightmode");
    document.getElementById("user_panel").classList.toggle("nightmode");
    document.getElementById("community_title_line").classList.toggle("nightmode");
    document.getElementById("profileArrow").classList.toggle("nightmode");
    
    var communities = document.getElementsByClassName("community_cell_container");
    for(var i = 0; i < communities.length; i++)
    {
        communities.item(i).classList.toggle("nightmode");
    }

    var buttons = document.getElementsByClassName("panel_button");
    for(var i = 0; i < buttons.length; i++)
    {
        buttons.item(i).classList.toggle("nightmode");
    }
}

/*
 * Summary: Enables / disables the side panel
 */
var sidePanelOn = true;
function openSidePanel() {      
    sidePanelOn = !sidePanelOn;
    document.getElementById('community_panel').style.display = sidePanelOn ? "block" : "none";
    document.getElementById('openSideBarButton').style.backgroundImage = sidePanelOn ? "url(../imgs/sidepanelButtonHover.png)" : "url(../imgs/sidepanelButton.png)";
    document.getElementById('middle_panel').style.paddingLeft = sidePanelOn ? "270px" : "10px";
}

/*
 * Summary: Enables / disables the profile panel
 */
var onProfile = false;
function openProfilePanel() {      
    onProfile = !onProfile;
    document.getElementById('user_panel').style.display = onProfile ? "block" : "none";
    document.getElementById('profileButton').style.backgroundImage = onProfile ? "url(../imgs/profileButtonEnabled.png)" : "url(../imgs/profileButton.png)";
}

/*
 * Summary: Enables the register form to create a new user
 */
function enableRegisterForm() {
    document.getElementById('registerButton').style.display = "none";
    document.getElementById('registerUserForm').style.display = "block";
}

/*
 * Summary: Enables / disables the grid view
 */
var gridViewOn = true;
function toggleGridView() {      
    gridViewOn = !gridViewOn;
    document.getElementById('gridButton').style.backgroundImage = gridViewOn ? "url(../imgs/gridButtonEnabled.png)" : "url(../imgs/gridButton.png)";
    document.getElementById('main_feed').style.gridTemplateColumns = gridViewOn ? "repeat(auto-fill, minmax(230px, 1fr))" : "repeat(auto-fill, minmax(100%, 1fr))";

    var pictures = document.getElementsByClassName("picturePreview");
    for(var i = 0; i < pictures.length; i++)
    {
        pictures.item(i).style.height = gridViewOn ? "260px" : "500px";
        pictures.item(i).style.width  = gridViewOn ? "230px" : "100%";
    }
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