<!DOCTYPE html>
<html>
<head>

<!-- ************
//
//	Assingment Tracker Fall 2021
//	Created by Peter Jacobson
//	Procedure Code
//
************* -->

</head>
<table border="1" align="center">
<tr>
  <td>Class Standing</td>
  <td>Count</td>

</tr>
<body>

<?php
// include credientals which should be stored outside your root directory (i.e. outside public_html)
// do NOT use '../' in file path
require_once '/home/SOU/jacobsonp/dbconfig.php';

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
//echo "Connected successfully  <br>  <br>";

// if someone posts data 
if(isset($_POST['submit'])) {
	// get employee name value from post
	$course_id=$_POST['course_id'];
	// echo $course_id;
	// build query
  	$query = ("call student_standing_by_course($course_id)"); // string needs quotes
  	// if query is not successful
   // if (!mysqli_query($mysqli, $query)) {
     //   die('An error occurred.');
  // } else {
    	$retval = mysqli_query($mysqli, $query);  
		// if one or more rows were returned
		if(mysqli_num_rows($retval) > 0){ 
			// while there is data to be fetch
			while($row = mysqli_fetch_assoc($retval)) {  
				// access data an build HTML table row
    			echo 
    	  			"
    	  			<tr>
    	  			<td>{$row['Class Standing']}</td>  
          			<td>{$row['Count']}</td>
   		  			</tr>\n";  
			} // end while
		} else {  
			echo "No results found";  
		}
	//}
}
// free result set
mysqli_free_result($retval);
//close connection
mysqli_close($mysqli); 
?> <!-- signifiies the end of PHP code -->

</body>
</html>