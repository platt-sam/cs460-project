<!DOCTYPE html>
<html>
<head>
<!-- 
*	Filename: assignment_to_course_proc.php
* 	Author: Ronin Ganoot
* 	Last Updated: Fall 2021
*	Class: CS460
*
-->
</head>

<body>
<table border="1" align="center">
        <tr>
            <td>Course Title</td>
	    <td>Assignment ID</td>
            <td>Assignment Name</td>
            <td>Status</td>
            <td>Date Started</td>
            <td>Date Completed</td>
        </tr>
	<body>
<?php
// include credientals which should be stored outside your root directory (i.e. outside public_html)
// do NOT use '../' in file path
require_once '/home/SOU/ganootr/dbconfig.php';

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
    // get assignment id and course id value from post
    	$assignment_id=$_POST['assignment_id'];
	$course_id=$_POST['course_id'];
	//check values to see if they come back correctly
	//echo $course_id;
	//echo $assignment_id;

    // build query
        $query = ("call Assignment_to_course_status ($assignment_id, $course_id)");
        $retval = mysqli_query($mysqli, $query);  
        // if one or more rows were returned
        if(mysqli_num_rows($retval) > 0){ 
            // while there is data to be fetch
            while($row = mysqli_fetch_assoc($retval)) {  
                // access data an build HTML table row
                echo 
                      "
                      <tr>
                      <td>{$row['course_title']}</td>  
                      <td>{$row['assignment_id']}</td>
		      <td>{$row['assignment_name']}</td>
		      <td>{$row['status']}</td>
		      <td>{$row['dateStarted']}</td>
		      <td>{$row['dateCompleted']}</td>
                         </tr>\n";  
            } // end while
        } else {  
            echo "No results found";  
        }
}
// free result set
mysqli_free_result($retval);
//close connection
mysqli_close($mysqli); 
?> <!-- signifiies the end of PHP code -->

</body>
</html>