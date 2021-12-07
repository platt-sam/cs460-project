<!DOCTYPE html>
<html>
<head>
</head>

<body>
<h1><a href="https://cs460.sou.edu/~platts1/f21_assignmentTracker/">Assignment Tracker</a></h1>
<h2>Update Task Results</h2>
	<h4> Author: Nayeli Esqueda Alvarado </h4>
	<h4 style="display:inline;">Description: </h4>
	<p style="display:inline;"> This page will display Student ID, Assignment ID, and updated Status.</p>
<br/>
<br/>
<table border="1" align="center">
  <tr>
      <td>student_id</td>
      <td>assignment_id</td>
      <td>status</td>
  </tr>
<body>
<?php // signifies the start of PHP code
// notice this uses a different config file than the other examples
require_once '/home/SOU/esquedaan/dbconfig_project.php';

// Turn error reporting on
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', '1');

$mysqli = mysqli_connect($hostname,$username,$password,$schema);

if (mysqli_connect_errno()) {
  printf("connection failed: " . mysqli_connect_errno());
  exit();
}
echo "Connected successfully  <br>  <br>";

if(isset($_POST['submit'])) {
  $student_id = $_POST['student_id']; 
  $assignment_id = $_POST['assignment_id'];
  $status = $_POST['status'];
  echo $student_id;
  echo $assignment_id;
  echo $status;
  //building query
  $query = ("call update_task($student_id, $assignment_id, '$status')");
  
  $retval = mysqli_query($mysqli, $query);
    if(mysqli_num_rows($retval) > 0){
      while($row = mysqli_fetch_assoc($retval)){
        echo  
          "
          <tr>
          <td>{$row['student_id']}</td>
          <td>{$row['assignment_id']}</td>
          <td>{$row['status']}</td>
          </tr>\n";
        }
      } else {
        echo "No Results";
    }
}
mysqli_free_result($retval);
mysqli_close($mysqli);
?>
</body>
</html>