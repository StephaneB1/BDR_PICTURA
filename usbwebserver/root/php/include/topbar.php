<!--
/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary:     HTML for the top bar
 */ 
-->

<!-- Top bar -->
<div class="topPanel" id="topSearchPanel">

    <!-- Side panel button and logo -->
    <div class="topPanelLeft">
        <button class="topPanelButton" id="openSideBarButton" onclick="openSidePanel()"></button>
        <img src="imgs/pictura_logo.png" style="height: 30px;" />
    </div>

    <!-- Search input -->
    <div class="searchbarContainer">
        <input type="text" placeholder="Search.." id="searchBar">
    </div>

    <!-- Additional buttons and profile panel -->
    <div class="topPanelRight">
        <button class="topPanelButton" id="gridButton" onclick="toggleGridView()"></button>
        <button class="topPanelButton" id="nightmodeButton" onclick="switchNightMode()"></button>
        <button class="topPanelButton" id="profileButton" onclick="openProfilePanel()"></button>
    </div>

    <div class="shadow"></div>

</div> 