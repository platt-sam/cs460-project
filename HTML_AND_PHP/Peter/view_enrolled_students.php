<!DOCTYPE html>
<html>
<head>
<!-- 
*	Filename: view_enrolled_students.php
* 	Author: Ronin Ganoot
* 	Last Updated: Fall 2021
*	Class: CS460
*
*
-->
</head>
<body>
<table border="1" align="center">
     <tr>
  	 <th>Section ID</th>
  	 <th>Amount of Students Enrolled</th>
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

if(isset($_POST['submit'])) {
	/* get value from post and store it in local variable */
	$section_id = $_POST['section_id'];
	//check to see if the correct input is there
	echo $section_id;

	$stmt = "select * from v_enrolled_students where section_id = $section_id";
  
	/* execute the prepared statement */
	$stmt->execute() or die($dbconnect->error);
	
	/* get result set */
	$retval = $stmt->get_result();  
    if ($retval->num_rows > 0){
		/* while there is more data to fetch data in row array, then output the fields of
		interest by field name into the HTML table */
		while($row = $retval->fetch_assoc($retval)) {  
			echo "
				<tr>
				<td>{$row['section_id']}</td>  
				<td>{$row['Amount of Students Enrolled']}</td>
				</tr>\n";  
		}
		echo "</table>"; // when there are not more rows to process close the HTML table
	} else {
		echo "</table><br><br>No results found "; // no data found, close table, print message
	}
	// free result set (acts on statement object not mysqli object)
	$retval->free_result();
	//close connection using mysqli object
	$dbconnect->close(); 
}
?> <!-- signifiies the end of PHP code -->
</body>