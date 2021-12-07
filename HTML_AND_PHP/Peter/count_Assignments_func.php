<!DOCTYPE html>
<html>
<head>
<!-- 
*	Filename: count_Assignments_func.php
* 	Author: Ronin Ganoot
* 	Last Updated: Fall 2021
*	Class: CS460
*
*	Purpose: This file executes the stored function count_Assignments for all assignments in the assignment table.
*
-->
</head>

<body>
<h2> Function Execution </h2>
<h4> Author: Ronin Ganoot </h4>
<h4 style="display:inline;"> Description: </h4>
<p style="display:inline;"> gets the amount assignments associated with each section. <b>assignment_count</b>, which takes 
in a <b>section id</b> as a parameter and returns the count of all assignments in that section. 
It currently executes for all section ids in the section table. The count for section 3398 should be 1.</p>
<br/>
<br/>
<h4 style="display:inline;"> Justification: </h4>
<p style="display:inline;"> This is useful to see how many assignments are associated with each section. </p>
<br/>
<br/>
<!-- start the table -->
<table border="1" align="center">
<tr>
  <th>Section ID</th>
  <th>Assignment name</th>
  <th>Count</th>

</tr>


<?php
require_once '/home/SOU/ganootr/dbconfig.php';

// Turn error reporting on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

//create connection
$dbconnect = new mysqli ($hostname,$username,$password,$schema);

if ($dbconnect->connect_error) {
	die("Database connection failed: " . $dbconnect->connect_error);
} else {
	/* The statement below includes the stored function count_Assignments */
	$result = $dbconnect->query('select *, count_Assignments(section_id) as num_of_Assignments from assignment');   
	while($row = $result->fetch_assoc()) {  
		/* num_of_Assignments column is based on name given in query above.
		it represents the value returned by the count_Assignments function */
   		echo "
    	  	<tr>
    	  	<td>{$row['section_id']}</td>
		<td>{$row['assignment_name']}</td>   
          	<td>{$row['num_of_Assignments']}</td>
   		  	</tr>\n"; 
	} 
	// free result set (acts on statement object not mysqli object)
	$result->free_result();
	//close connection using mysqli object
	$dbconnect->close(); 
}
?> <!-- signifiies the end of PHP code -->
</table>
</body>
</html>