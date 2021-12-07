<!DOCTYPE html>
<html>
<head>
</head>

<body>
<h1><a href='https://cs460.sou.edu/~platts1/f21_assignmentTracker/'>Assignment Tracker</a></h1>
<h2> Student Courses</h2>
<h4> Author: Nayeli Esqueda</h4>
<!-- start the table -->
<table border="1" align="center">
<tr>
  <th>Student</th>
  <th>Course Total</th>
</tr>


<?php
// notice this uses a different config file than the other examples
require_once '/home/SOU/esquedaan/dbconfig_project.php';

// Turn error reporting on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

$conn = new mysqli($hostname,$username,$password,$schema);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

if(isset($_POST['submit'])) { 
	$student_id = $_POST["student_id"]; 
	$result = $conn->query("select course_count($student_id) AS totalC");
	while($row = $result->fetch_assoc()) {
		echo "
		<tr>
		<td>{$student_id}</td>
		<td>{$row['totalC']}</td>
			</tr>\n";
	}
	$result->free_result();
	$conn->close();
}
?> <!--end of PHP code -->
</table>
</body>
</html>