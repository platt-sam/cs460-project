<!DOCTYPE html>
<html>
<head>
<!-- 
*	Filename: add_employee.html
* 	Author: Ronin Ganoot
* 	Last Updated: Fall 2021
*	Class: CS460
*
* 
*
-->
</head>
<body>
<h2> Trigger Execution Results </h2>
<h4> Author: Ronin Ganoot</h4>

<br/>
<br/>
<!-- start the table -->
<table border="1" align="center">
<tr>
  <th>ID</th>
  <th>Assignment ID</th>
  <th>Old Assignment Name</th>
  <th>New Assignment Name</th>
  <th>Date Changed</th>
  <th>Changed by</th>


<?php // signifies the start of PHP code
require_once '/home/SOU/ganootr/dbconfig.php';

// Turn error report on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

// Create connection 
$conn = new mysqli($hostname,$username,$password,$schema);

// Check connection 
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// if someone posts data 
if(isset($_POST['submit'])) {
    // get assignment id value from the post
    $assignment_name=$_POST['assignment_name'];
    $assignment_id=$_POST['assignment_id'];

    // build query
    $res_set = $conn->multi_query("start transaction; UPDATE assignment SET assignment_name = '$assignment_name' WHERE assignment_id = '$assignment_id'; 
	select * from assignment_updates; rollback;"); // string needs quotes
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