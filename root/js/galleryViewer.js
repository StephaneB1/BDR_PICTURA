/**
 * ETML
 * Author: Robin Demarta
 * Date: 12.05.2017
 * Summary: Shows up the image viewer with the corresponding image and close it on click
 */

var fadingTime = 100;

//Display viewer on item click
$(".viewer-item").click(function(data) {
	//The bellow "if" statement prevents click event from happening when clicking the child elements
	if (data.target == this) {
		if ($(this).is(".video")) { //If it's a video -> get video source
			//Remove eventual background image
			$("#gallery-viewer").css("background-image", "")
				.fadeIn(fadingTime)
				.html("<video id='viewerVideo'onclick='this.paused ? this.play() : this.pause();' controls>"
				+"<source src='"+$(this).attr("value")+"' type='video/mp4'></video>");
		} else {
			$("#gallery-viewer").css("background-image", $(this).css("background-image")).html("").fadeIn(fadingTime);
		}
    }
});

//Prevent viewer from disapearing when clicking on video
$("#gallery-viewer" ).click(function(data) {
	if (data.target == this) {
		$(this).html("").fadeOut(fadingTime);
	}
});