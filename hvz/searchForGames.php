<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Search For Games
 * Location:        "/searchForGames.php"
 * 
 * Description:     A search script to search for games
 * 
 * Author:          John Gold
 * Date:            11/5/12
 * Version:         1.1
 * *****************************************************************************
 * *****************************************************************************
 */
 


// initialise the app
// init.php contains all facebook sdk info, session control, and the works
require_once('core/init.php');
include('inc/phpfuncs.php');

?>

<html>

<?php

// generate page title and include the header
// $header_title is passed in the header.php file
$header_title = "Search for Games";
include('inc/header.php');

?>


<body>
    
<div data-role="page">

		<div data-role="header">
				<a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
				<h1>SEARCH</h1>
		</div><!-- /header -->

		<div data-role="content">
				<ul data-role="listview" data-inset="true" >	
						<div data-role="collapsible-set" data-theme="a" data-iconpos="right" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d">
								<div data-role="collapsible" data-inset="true">
										<h3>Search by Game Name</h3>
										<ul data-role="listview" data-inset="true" id="beforeSearchByGameName">
												<div id="searchByGameName">
        												<input id="game_name" name="game_name" type="text" placeholder="Enter Game Name" autofocus required>
        												<input id="latitude" name="latitude" type="hidden">
        												<input id="longitude" name="longitude" type="hidden">
					       			 					<input id="submitGame" data-theme="a" type="button" value="Go">
												</div>
												<div id="GameNameResults"></div>
										</ul>
	        					</div><!-- end of collapsible -->
	        	
	        					<div data-role="collapsible" data-inset="true">
										<h3>Search by Friend Name</h3>
										<ul data-role="listview" data-inset="true" id="beforeSearchByFriendName">
												<div id="searchByFriendName">
        												<input id="friend_name" name="friend_name" type="text" placeholder="Enter Friend Name" autofocus required>
        												<input id="latitude" name="longitude" type="hidden">
        												<input id="longitude" name="longitude" type="hidden">
														<input id="submitFriend" type="button" data-theme="a" value="Go">		
												</div>
												<div id="FriendNameResults"></div>
										</ul>
	        					</div> <!-- end of collapsible -->
	        					
	        					<div id="beforeSearchNearby">
	        							<input id="latitude" name="longitude" type="hidden">
	        							<input id="longitude" name="longitude" type="hidden">
	        							<input id="submitNearby" type="button" data-theme="a" value="Search nearby">
	        					</div>
	        					<div id="NearbyResults"></div>
	        			</div> <!-- /end of collapsible set -->
				</ul> <!-- end of listview -->
		</div><!-- /content -->
	
	<!--submit search for game name script -->
	<script type="text/javascript">
	$("#submitGame").click(function(event) {
		$.post("searchByGameName.php", {
			user_id: <?=$user_id?>,
			game_name: $("#game_name").val(), 
			latitude: $("#latitude").val(), 
			longitude: $("#longitude").val() 
		}, function(data){
			$("#GameNameResults").html(data);
		});
	});
	<!-- for searching by friend -->
	$("#submitFriend").click(function(event) {
		$.post("searchByFriendName.php", {
			user_id: <?=$user_id?>,
			friend_name: $("#friend_name").val(), 
			latitude: $("#latitude").val(), 
			longitude: $("#longitude").val() 
		}, function(data){
			$("#FriendNameResults").html(data);
		});
	});
	<!-- for searching nearby-->
	$("#submitNearby").click(function(event) {
		$.post("gamesNearby2.php", {
			user_id: <?=$user_id?>,
			friend_name: $("#friend_name").val(), 
			latitude: $("#latitude").val(), 
			longitude: $("#longitude").val()
		}, function(data){
			$("#NearbyResults").html(data);
		});
	});
	</script>
	
	<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
	<?php
		include('inc/navBar.php');
	 ?>
	
	</div><!-- /page -->
	
    </body>
    <script type="text/javascript" src="navBarSlide.js"></script>
	<script type="text/javascript" src="geolocation.js"></script>

	
</html>