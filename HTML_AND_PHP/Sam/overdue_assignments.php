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
	<title>Overdue Assignments</title>
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Overdue Assignments (stored procedure)</h2>
	</header>
	
	<table align=center>
		<tr>
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
$conn = new mysqli($hostname,$username,$password,$schema);

// Check connection 
if ($conn->connect_error) {
	die("Database connection failed: " . $conn->connect_error);
}
# echo "Connected successfully  <br>  <br>";

// if someone posts data 
if(isset($_POST['submit'])) {
	// get student id value from post
	$student_id=$_POST['student_id'];

	// build query
	$res_set = $conn->multi_query("call overdue_assignments($student_id, @course_title, @assignment_name, @status, @dateStarted, @dueDate); select @course_title, @assignment_name, @status, @dateStarted, @dueDate;"); // string needs quotes
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
			}
		} while ($conn->more_results() && $conn->next_result());
	} else {
		echo "no results";
	}
}
//close connection
$conn->close(); 
?> <!-- signifiies the end of PHP code -->
</body>
</html>