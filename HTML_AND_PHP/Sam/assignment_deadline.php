<!DOCTYPE html>
<html>
<head>

<!-- ************
//
//	CS 460 Fall 2021
//	Created by Dr. Vanderberg
//	Example that executes query and intergrates with HTML to build a result table 
//
************* -->
	<link href="styles.css" rel="stylesheet" />
	<title>Assignment Deadline</title>
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Assignment Deadline (function)</h2>
	</header>
	<table align=center>
		<tr>
			<th>Assignment ID</th>
			<th>Assignment Deadline</th>
			<th>Days Until Due</th>
		</tr>
<?php
// include credientals which should be stored outside your root directory (i.e. outside public_html)
// do NOT use '../' in file path
require_once '/home/SOU/platts1/dbconfig_project.php';

// Turn error reporting on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

// Create connection 
$mysqli = mysqli_connect($hostname,$username,$password,$schema);

// Check connection 
if (mysqli_connect_errno()) {
    printf("Connection failed: " . mysqli_connect_errno());
    exit();
}
# echo "Connected successfully  <br>  <br>";

// if someone posts data 
if(isset($_POST['submit'])) {
	// get student id value from post
	$assignment_id=$_POST['assignment_id'];

	// build query
  	$query = "select assignment_deadline('$assignment_id') AS 'Assignment Deadline', DATEDIFF(assignment_deadline('$assignment_id'), NOW()) AS 'Days Until Due'"; // string needs quotes
  	// if query is not successful
    if (!mysqli_query($mysqli, $query)) {
        die('An error occurred.');
    } else {
    	$retval = mysqli_query($mysqli, $query);  
		// if one or more rows were returned
		if(mysqli_num_rows($retval) > 0){  
			// whilte there is data to be fetch
			while($row = mysqli_fetch_assoc($retval)) {  
				// access data an build HTML table row
    			echo 
    	  			"
    	  			<tr>
    	  			<td>{$assignment_id}</td>  
          			<td>{$row['Assignment Deadline']}</td>
					<td>{$row['Days Until Due']}</td>
   		  			</tr>\n";  
			} // end while
		} else {  
			echo "No results found";  
		}
	}
}
// free result set
mysqli_free_result($retval);
//close connection
mysqli_close($mysqli); 
?> <!-- signifiies the end of PHP code -->
</body>
</html>