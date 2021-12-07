<!DOCTYPE html>
<html>
<head>
</head>

<body>
<h1><a href='https://cs460.sou.edu/~platts1/f21_assignmentTracker/'>Assignment Tracker</a></h1>
<h2> Student Credit Check Results</h2>
<h4> Author: Nayeli Esqueda</h4>
<br/>
<br/>
<table border="1" align="center">
  <tr>
      <td>Student ID</td>
      <td>Student First</td>
      <td>Student Last</td>
      <td>Student DOB</td>
      <td>Student Credits</td>
  </tr>
<body>

<?php // signifies the start of PHP code
require_once '/home/SOU/esquedaan/dbconfig_project.php';

// Turn error report on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

$dbconnect = new mysqli($hostname,$username,$password,$schema);

if ($dbconnect->connect_error) {
  die("Database connection failed: " . $dbconnect->connect_error);
}

if(isset($_POST['submit'])) {
    $student_credits=$_POST['student_credits'];
	$student_id=$_POST['student_id'];

    $res_set = $dbconnect->multi_query("start transaction; UPDATE student SET student_credits = '$student_credits' 
        WHERE student_id = '$student_id'; SELECT * FROM student WHERE student_id ='$student_id';
        rollback;");

    if($res_set) {
        do {
            if ($result = $dbconnect->store_result()) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach($row as $key => $value) {
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }
            }
        } while ($dbconnect->more_results() && $dbconnect->next_result());
    } else {
        echo "no results";
    }
}
// close connection
$dbconnect->close();
?> <!-- signifiies the end of PHP code -->
</body>
</html>