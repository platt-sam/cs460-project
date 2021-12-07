<!DOCTYPE html>
<html>
<head>

<!-- Basic form to accept input via a webinterface 
Author: Dr. Vanderberg
Date: 1/31/2018
Class: CS460
-->
	<link href="styles.css" rel="stylesheet" />
	<title>Overdue Assigments form</title>
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Overdue Assignments (stored procedure)</h2>
	</header>
	<p>
		<b>Implemented by: </b>
		Sam Platt
		<br/>

		<b>Description: </b>
		Will show all tasks that are overdue and incomplete.
		<br/>

		<b>Justification: </b>
		Useful for students who want to view which of their assignments are incomplete and overdue.
		<br/>

		<b>Expected Output: </b>
		A table displaying the course title, task status, assignment name, date started, and assignment's due date. 
		If the student has no overdue assignments the output will be a table with a header row and one empty row.
	</p>

	<hr/>

	<p>Please enter the following information to view overdue assignments: </p>

	<!-- use the POST method to pass data to overdue_assignments.php -->
  	<form action="overdue_assignments.php" method="POST">
      	Student ID: <select name="student_id" type="number">
		  	<?php
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

				// build query
				$res_set = $conn->multi_query("select student_id from student;"); // string needs quotes
				// if query is not successful
  				if ($res_set) {
	  				do {
		  				if ($result = $conn->store_result()) {
			  				while ($row = $result->fetch_assoc()) {
				  				foreach($row as $key => $value) {
					  				echo "<option value='$value'>$value</option>";
				  				}
			  				}
		  				}
	  				} while ($conn->more_results() && $conn->next_result());
  				} else {
	  				echo "<option value='NULL'>NULL</option>";
  				}
			?>
	  	</select>
	  	<br/><br/>
      
      	<input type="submit" value="Submit" name="submit">

	</form>
</body>

</html>