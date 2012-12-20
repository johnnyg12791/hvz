<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Settings
 * Location:        "/settings.php"
 * 
 * Description:     Settings for the app....
 * 
 * Author:          author
 * Date:            date
 * Version:         1.1
 * *****************************************************************************
 * *****************************************************************************
 */
 


// initialise the app
// init.php contains all facebook sdk info, session control, and the works
require_once('core/init.php');


?>

<html>

<?php

// generate page title and include the header
// $header_title is passed in the header.php file
$header_title = "Welcome";
include('inc/header.php');


// copy up to here and paste in all your new files. This will make starting a new file 
// a hundred times easier

?>
    
    <div data-role="page">

	<div data-role="header">
		<a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
		<h1>Settings</h1>
	</div><!-- /header -->

	<div data-role="content">
	
			<div data-role="fieldcontain">
				<label for="music">Music:</label>
				<select name="music" id="music" data-role="slider">
					<option value="off">Off</option>
					<option value="on">On</option>
				</select>
			</div>

			<div data-role="fieldcontain">
				<label for="sound">Sound:</label>
				<select name="sound" id="sound" data-role="slider">
					<option value="off">Off</option>
					<option value="on">On</option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<label for="notifications">Notifications:</label>
				<select name="notifications" id="notifications" data-role="slider">
					<option value="off">Off</option>
					<option value="on">On</option>
				</select>
			</div>
	
	</div><!-- /content -->
	
	<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
	
	<?php
		include('inc/navBar.php');
	?>

	<!--#include virtual="inc/navBar.php" -->
	
	</div><!-- /page -->
	
    </body>

	
</html>