
/*
 * @brief Enables / disables night mode
 */
function switchNightMode() {
    document.getElementById("main_container").classList.toggle("nightmode");
    document.getElementById("community_panel").classList.toggle("nightmode");
    document.getElementById("feed_panel").classList.toggle("nightmode");
    document.getElementById("user_panel").classList.toggle("nightmode");
    document.getElementById("community_title_line").classList.toggle("nightmode");
    document.getElementById("username_highlight").classList.toggle("nightmode");
    
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

// Hide nav on scroll function
var nav = document.querySelector('.mainNav');
var position = 0;
window.addEventListener('scroll', function() {
    document.getElementById("topNavBar").style.display = position < window.pageYOffset ? "none" : "block";
    position = window.pageYOffset;
})


