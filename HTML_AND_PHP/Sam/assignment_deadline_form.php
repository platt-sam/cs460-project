<!DOCTYPE html>
<html>
<head>

<!-- Basic form to accept input via a webinterface 
Author: Dr. Vanderberg
Date: 1/31/2018
Class: CS460
-->
	<link href="styles.css" rel="stylesheet" />
	<title>Assignment Deadline form</title>
</head>

<body>
	<header>
		<h1><a href="index.html">Assignment Tracker</a></h1>
		<h2>Assignment Deadline (function)</h2>
	</header>
	<p>
		<b>Implemented by: </b>
		Sam Platt
		<br/>

		<b>Description: </b>
		Gets the due date for an assignment, and how many days/hours remain until the due date. 
		<br/>

		<b>Justification: </b>
		Helpful for students because it informs them when their assignment is due and how long they have to complete it.
		<br/>

		<b>Expected Output</b>
		A table displaying the assignment id, the assigment's due date, and how many days remain until that due date.
		<br/>
	</p>

	<hr/>

	<p>Please enter the following information to view assignment deadline: </p>

	<!-- use the POST method to pass data to incomplete_tasks.php -->
  	<form action="assignment_deadline.php" method="POST">
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
      	<br/><br/>
      	<input type="submit" value="Submit" name="submit">

	</form>
</body>

</html>