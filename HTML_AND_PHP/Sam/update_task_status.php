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
	<title>Update Task Status</title>
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Update Task Status (trigger)</h2>
	</header>
	<table align=center>
		<tr>
			<th>Student ID</th>
			<th>Assignment ID</th>
			<th>Status</th>
			<th>Date Started</th>
			<th>Date Completed</th>
		</tr>
<?php
// include credientals which should be stored outside your root directory (i.e. outside public_html)
// do NOT use '../' in file path
require_once '/home/SOU/platts1/dbconfig_project.php';

// Turn error reporting on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

// Create connection 
$conn = new mysqli($hostname,$username,$password,$schema);

// Check connection 
if ($conn->connect_error) {
	die("Database connection failed: " . $conn->connect_error);
}
# echo "Connected successfully  <br>  <br>";

// if someone posts data 
if(isset($_POST['submit'])) {
	// get student id value from post
	$status=$_POST['status'];
	$student_id=$_POST['student_id'];
	$assignment_id=$_POST['assignment_id'];

	// build query
	$res_set = $conn->multi_query("update task set status = '$status' where student_id = '$student_id' and assignment_id = '$assignment_id'; select * from task where student_id = '$student_id' and assignment_id = '$assignment_id';"); // string needs quotes
  	// if query is not successful
    if ($res_set) {
		do {
			if ($result = $conn->store_result()) {
				while ($row = $result->fetch_assoc()) {

					echo "<tr>";
					foreach($row as $key => $value) {
						echo "<td>$value</td>";
					}
					echo "</tr>";
				}
			} else {
				echo "<p>No tasks were found in the database with a Student ID of <b>$student_id</b> and Assignment ID of <b>$assignment_id</b></p>";
			}
		} while ($conn->more_results() && $conn->next_result());
	} else {
		// echo "<p>No tasks were found in the database with a Student ID of <b>$student_id</b> and Assignment ID of <b>$assignment_id</b></p>";
		echo "<p>Error.</p>";
	}
}
//close connection
$conn->close(); 
?> <!-- signifiies the end of PHP code -->
</table>

</body>
</html>