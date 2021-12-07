<!DOCTYPE html>
<html>
<head>

<!-- Basic form to accept input via a webinterface 
Author: Dr. Vanderberg
Date: 1/31/2018
Class: CS460
-->
	<link href="styles.css" rel="stylesheet" />
	<title>Incomplete Tasks form</title>
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Incomplete Tasks (view)</h2>
	</header>

	<p>
		<b>Implemented by: </b>
		Sam Platt
		<br/>

		<b>Description: </b>
		Will show all tasks assigned to a student that are not complete.
		<br/>

		<b>Justification: </b>
		Useful for students who want to view which of their tasks have not been completed.
		<br/>

		<b>Expected Output: </b>
		A table displaying the student id, course title, task status, assignment name, date started, and the assignment's due date.
		If the student has no incomplete tasks the output will be a table with a header row and one empty row.
		<br/>
	</p>

	<hr/>

	<p>Please enter the following information to view incomplete tasks: </p>

	<!-- use the POST method to pass data to incomplete_tasks.php -->
  	<form action="incomplete_tasks.php" method="POST">
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
	  	<br /><br />
      	<input type="submit" value="Submit" name="submit" />

	</form>
</body>

</html>