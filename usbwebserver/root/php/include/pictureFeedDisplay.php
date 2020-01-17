

<?php    
// Author
$user = $_SESSION["p"]; // Login check already made

if (!empty($user)) {
    $feed = $db->getUserFeedPictures($user["pseudo"]);
} else {
    if(!empty($_SESSION["n"]))
}
$feed = $db->getCommunityPhotos($_SESSION["n"]);

if ($isLoggedIn) {
    $feed = $db->getUserFeedPictures($user["pseudo"]);
    
    for ($i = 0; $i < count($feed); ++$i) {
        $post_community = $db->getCommunityByName($feed[$i]["nomCommunaute"])[0];

        echo 
        '<a href="picture_fullview.php?id=' . htmlentities($feed[$i]["id"]) . '" class="picturePreview" id='. htmlentities($feed[$i]["id"]) . ' style="background-image: url(files/'. htmlentities($feed[$i]["urlPhoto"]) .')" >
            <div class="picturePreviewShadowTop"></div>
            <div class="picturePreviewShadowBottom"></div>	
            
            <div class="picturePreviewHeader">
                <div class="picturePreviewHeaderTitle">'. htmlentities($feed[$i]["titre"]) . '</div>
                <div class="picturePreviewHeaderSubtitle">'. htmlentities($feed[$i]["pseudoUtilisateur"]) . ' • ' . htmlentities($feed[$i]["dateHeureAjout"]) . '</div>
            </div>
                        
            <div class="picturePreviewFooter">
                <div class="picturePreviewFooterButton" style="background-image: url(files/'. htmlentities($post_community["imageDeProfil"]) .'), url(files/community_default.PNG);"></div>	
            ';
            
            $userLiked = $db->checkIfUserLikedAPicture($user["pseudo"], $feed[$i]["id"]);
            if($userLiked) {
                echo '<button onclick="likePicture('.htmlentities($user["pseudo"]).','.htmlentities($feed[$i]["id"]).')" class="picturePreviewFooterButton" style="background-image: url(/imgs/like_on.png);"></button>';
            } else {
                echo '<button onclick="likePicture('.htmlentities($user["pseudo"]).','.htmlentities($feed[$i]["id"]).')" class="picturePreviewFooterButton" style="background-image: url(/imgs/like_off.png);"></button>';
            }
            
            echo '</div></a>';
    }
}
?>