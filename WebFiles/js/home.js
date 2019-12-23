
// ===== TEMPORARY SETUP ===== //
// This setup will be handled by the SQL/PHP
const picturesData = [
	{
		id:			     "1",
		header_title: 	 "Waterfall in Costa Rica",
		header_subtitle: "@steph123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "2",
		header_title: 	 "A cute puppy",
		header_subtitle: "@robin123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "3",
		header_title: 	 "Skeedadle skidoodle",
		header_subtitle: "@simon123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "4",
		header_title: 	 "My BDR project :)",
		header_subtitle: "@steph123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "5",
		header_title: 	 "Hello",
		header_subtitle: "@robin123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "6",
		header_title: 	 "A cute puppy",
		header_subtitle: "@robin123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "7",
		header_title: 	 "Skeedadle skidoodle",
		header_subtitle: "@simon123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "8",
		header_title: 	 "My BDR project :)",
		header_subtitle: "@steph123 - X days ago",
		img_src: 		 "http://...",
	}, 
	{
		id:			     "9",
		header_title: 	 "Hello",
		header_subtitle: "@robin123 - X days ago",
		img_src: 		 "http://...",
	}
];

// ===== INITIAL SETUP ===== //
function initHomeFeed() {
	setupHomeFeed(picturesData);
}

/*
 * @brief HTML Template for a picture preview
 * @param picture : the picture to display
 * @return the template for a picture preview
 */
function picturePreviewTemplate(picturesData) {
	return `
		<a href="html/picture_fullview.html" class="picturePreview" id=${picturesData.id}>
			<div class="picturePreviewShadowTop"></div>
			<div class="picturePreviewShadowBottom"></div>	
			
			<div class="picturePreviewHeader">
				<div class="picturePreviewHeaderTitle">${picturesData.header_title}</div>
				<div class="picturePreviewHeaderSubtitle">${picturesData.header_subtitle}</div>
				<button class="picturePreviewOptionsButton"></button>
			</div>
						
			<div class="picturePreviewFooter">
				<button class="picturePreviewFooterButton"></button>	
				<button class="picturePreviewFooterButton"></button>
			</div>
		</a>
		`
}

/*
 * @brief Sets the home feed using the picturePreviewTemplate
 * @param pictures : list of all the pictures to display
 * 					 on the home feed section
 */
function setupHomeFeed(pictures) {
	document.getElementById("homeFeed").innerHTML = `
		${pictures.map(picturePreviewTemplate).join('')}
	`;
	//setupPictureLinks(pictures);
}

/*
 * @brief Add onclick event to each pictures (enables data transfering to
 *        the full picture view)
 * @param pictures : all the loaded pictures  
 */
function setupPictureLinks(pictures) {
	for (i = 0; i < pictures.length; i++) {		
        document.getElementById(pictures[i].id).addEventListener("click",
            savepicture.bind(this, pictures[i]));
	}
}











