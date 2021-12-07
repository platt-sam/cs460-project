<!DOCTYPE html>
<html>
<head>

<!-- ************
//
//	Assingment Tracker Fall 2021
//	Created by Peter Jacobson
//	
//
************* -->

</head>

<body>
<table border="1" align="center">
<tr>
  <td>Student ID </td>
  <td>Student First</td>
  <td>Student Last</td>
</tr>

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
echo "Connected successfully  <br>  <br>";

// if someone posts data 
if(isset($_POST['submit'])) {
	// get section_id value from post
	$course_id=$_POST['course_id'];
	// build query
  	//$query = "select * from section where course_id=$course_id";

	$query = " select *
			from student
			where student_id in (select student_id from roster_entry where section_id in 
			(select section_id from section where course_id = $course_id))"; 

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
          			<td>{$row['student_first']}</td>
    	  			<td>{$row['student_last']}</td>
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