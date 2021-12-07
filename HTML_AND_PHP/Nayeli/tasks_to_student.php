<!DOCTYPE html>
<html>
<head>
<!-- 
*	Filename: tasks_to_students.php
* 	Author: Nayeli Esqueda Alvarado
* 	Last Updated: Fall 2021
*	Class: CS460
*
*	Purpose: This PHP file recieves data from tasks_to_student.html and returns the
*		student id, assignment id, and status of an assignment.
*		It uses prepared statements to prevent SQL injection. (still needs some work)
-->
</head>

<body>
<h1><a href="https://cs460.sou.edu/~platts1/f21_assignmentTracker/">Assignment Tracker</a></h1>
<h2>Tasks to a Student Results</h2>
	<h4> Author: Nayeli Esqueda Alvarado </h4>
	<h4 style="display:inline;">Description: </h4>
	<p style="display:inline;"> This page will display all assignments under the task table  
	for the student id that was entered. </p>
<br/>
<br/>
<!-- start of table to hold data return by query -->
<table border="1" align="center">
<tr>
  <th>Student Number: </th>
  <th>Assignment Number</th>
  <th>Assignment Status</th>
</tr>

<?php // signifies the start of PHP code, notice how the comment markers differ

// include credientals which should be stored outside your root directory (i.e. outside public_html)
// do NOT use '../' in file path
require_once '/home/SOU/esquedaan/dbconfig_project.php';

// Turn error reporting on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

// create connection to DB - OOP
$dbconnect = new mysqli($hostname,$username,$password,$schema);

if (mysqli_connect_errno()) {
  printf("Database connection failed: " . mysqli_connect_errno());
  exit();
}
//echo "Connected Successfully"; 

if(isset($_POST['submit'])) {
	$stmt = $dbconnect->prepare("select * from task where student_id = ?")
	  or die($dbconnect->error);
	$name = $_POST['student_id']; 

	$stmt->bind_param('i',$name); 

	$stmt->execute() or die($dbconnect->error);
	
	$retval = $stmt->get_result();  
    if ($retval->num_rows > 0){
		while($row = $retval->fetch_assoc()) {  
			echo "
				<tr>
				<td>{$row['student_id']}</td>  
				<td>{$row['assignment_id']}</td>
				<td>{$row['status']}</td>
				</tr>\n";  
		}
		echo "</table>"; 
	} else {
		echo "</table><br><br>No results found "; 
	}
	$retval->free_result();
	$dbconnect->close(); 	
}
?> <!-- signifiies the end of PHP code -->
</body>