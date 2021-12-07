<!DOCTYPE html>
<html>
<head>

<!-- Basic form to accept input via a webinterface 
Author: Dr. Vanderberg
Date: 1/31/2018
Class: CS460
-->
	<link href="styles.css" rel="stylesheet" />
	<title>Update Task Status form</title>
</head>

<body>
<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Update Task Status (trigger)</h2>
	</header>
	<p>
		<b>Implemented by: </b>
		Sam Platt
		<br/>

		<b>Description: </b>
		Ensures that the values for status are from the list of pre-defined values.
		<br/>

		<b>Justification: </b>
		Helpful so that it is easier for the student to be able to keep track of their tasks (if you use both not-done and incomplete filtering tasks becomes more difficult)
		<br/>

		<b>Expected Output: </b>
		
	</p>

	<hr/>

	<p>Please enter the following information to update task status: </p>

	<!-- use the POST method to pass data to incomplete_tasks.php -->
  	<form action="update_task_status.php" method="POST">

      	Assignment ID: <select name="assignment_id" type="number">
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
				$res_set = $conn->multi_query("select assignment_id from assignment;"); // string needs quotes
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
	  	<br><br>
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
	  	<br><br>
	  	New Task Status: <select name="status" type="text">
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
				$res_set = $conn->multi_query("select DISTINCT(status) from task ORDER BY status ASC;"); // string needs quotes
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
	  	<br><br>
		
      	<input type="submit" value="Submit" name="submit">

	</form>
</body>

</html>