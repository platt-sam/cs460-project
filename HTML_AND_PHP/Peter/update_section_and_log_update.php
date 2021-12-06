<!DOCTYPE html>
<html>
<head>

<!-- ************
//
//	Assingment Tracker Fall 2021
//	Created by Peter Jacobson
//	
//
************* -->

</head>

<body>
<table border="1" align="center">
<tr>
  <td>Primary ID</td>
  <td>Section ID</td>
  <td>Student ID</td>
  <td>Original Grade</td>
  <td>New Grade</td>
  <td>Update User</td>
  <td>Time Updated</td>
</tr>

<?php
// include credientals which should be stored outside your root directory (i.e. outside public_html)
// do NOT use '../' in file path
require_once '/home/SOU/jacobsonp/dbconfig.php';

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
    // get values from post
    $student_id=$_POST['student_id'];
    $section_id=$_POST['section_id'];
    $grade=$_POST['grade'];

    // build query
    $res_set = $conn->multi_query("update roster_entry set grade = '$grade' WHERE section_id = '$section_id' AND student_id = '$student_id'; select * from grade_change_log;"); // string needs quotes
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
?> <!-- signifiies the end of PHP code --></body>
</html>