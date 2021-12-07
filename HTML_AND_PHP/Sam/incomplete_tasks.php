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
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Incomplete Tasks (view)</h2>
	</header>
	<table align=center>
		<tr>
			<th>Student ID </th>
			<th>Course Title</th>
			<th>Status</th>
			<th>Assignment Name</th>
			<th>Date Started</th>
			<th>Due Date</th>
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
	$student_id=$_POST['student_id'];

	// build query
  	$query = "select * from incomplete_tasks where student_id = '$student_id'"; // string needs quotes
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
    	  			<td>{$row['student_id']}</td>  
          			<td>{$row['Course Title']}</td>
    	  			<td>{$row['Assignment Name']}</td>
					<td>{$row['Status']}</td>
					<td>{$row['Date Started']}</td>
					<td>{$row['Due Date']}</td>
   		  			</tr>\n";  
			} // end while
		} else {
			echo "<tr><td></td><td></td><td></td><td></td><td></td></tr>";
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