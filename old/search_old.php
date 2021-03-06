<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Search Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body{
font-family: helvetica;
}

</style>
</head>
<body>

<h1>Search Records</h1>

<p><a href="index.php">View All</a> | <b>Search</b> | <a href="new_record.php">Add New Record</a></p>

<form action="" method="POST">
<b>Search by Last Name: </b>
<input type="text" name="applicant_name"><br/><br/>
<b>Search by ID: </b>
<input type="number" name="applicant_id" value=""><br/><br/>
<b>Search by Date: </b>
<input type="date" name="date"><br/><br>

<input type='submit' name='search_submit' value='Search'/>
</form>
<br/>
<br/>

<?php
// connect to the database
include('connect-db.php');

$applicant_id = $_POST['applicant_id'];
$applicant_name = $_POST['applicant_name'];
$applicant_date = $_POST['date'];
if (isset($_POST['search_submit'])){

//echo "<p> ID: ".$applicant_id." date: ".$applicant_date."</p>";

// get the records from the database

if($applicant_name == ''){
   $new_applicant_name = "null";
}
else{
  $new_applicant_name = $applicant_name;
}

//echo "<p> ID: ".$applicant_id." name: ".$new_applicant_name."</p>";

if ($result = $mysqli->query("SELECT * FROM applicant_info WHERE id='".$applicant_id."' OR lastname LIKE '%".$new_applicant_name."%' OR DATE='".$applicant_date."'"))
{


// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo "<table border='1' cellpadding='8'>";

// set table headers
echo "<tr> <th>ID</th> <th>Name</th> <th>Address</th> <th>Phone</th> <th>Email Address</th> <th>Date</th> <th>Comments</th> <th>Status</th> <th>Set Status</th> <th></th> </tr>";

while ($row = $result->fetch_object())
{

//set row color based on approval status
if ($row->status == "approved"){

  $row_color = "green";
} elseif ($row->status == "rejected"){
  $row_color = "red";
} elseif ($row->status == "pending"){
  $row_color = "grey";
}

//change date format
$originalDate = $row->date;
$newDate = date("m/d/Y", strtotime($originalDate));



// set up a row for each record
echo "<tr style='background-color:".$row_color.";'>";
echo "<td>" . $row->id . "</td>";
echo "<td>" . $row->firstname . " " . $row->lastname . "</td>";
//echo "<td>" . $row->lastname . "</td>";
echo "<td>" . $row->address . "</br>" .$row->city .  ", " . $row->state . " " . $row->zip . "</td>";
//echo "<td>" . $row->city . "</td>";
//echo "<td>" . $row->state . "</td>";
//echo "<td>" . $row->zip . "</td>";
//echo "<td>" . $row->phone . "</td>";
echo "<td>(".substr($row->phone, 0, 3).") ".substr($row->phone, 3, 3)."-".substr($row->phone,6)."</td>";
echo "<td>" . $row->email . "</td>";
echo "<td>" . $newDate . "</td>";
echo "<td><form action='' method='POST'>
<input type='hidden' name='comment_id' value='".$row->id."'>
<textarea rows='5' name='comment' cols='25'>
".$row->comments."
</textarea>
<br/>
<input type='submit' name='submit1' value='Save Comment'/>
</form>
</td>";
echo "<td>" . $row->status . "</td>";
//echo "<td style='background-color:white';><a href='records.php?id=" . $row->id . "'>Set Status</a></td>";
echo "<td style='background-color:white';>
<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<select name='status'>
  <option value=''>Select...</option>
  <option value='approved'>Approved</option>
  <option value='rejected'>Rejected</option>
  <option value='pending'>Pending</option>
</select>

<input type='submit' name='submit' value='Submit'/>
</form>
</td>";
echo "<td style='background-color:white';><a href='delete.php?id=" . $row->id . "'>Delete</a></td>";
echo "</tr>";
}

echo "</table>";
}

// if there are no records in the database, display an alert message
else
{
echo "No results to display!";
}
}
// show an error if there is an issue with the database query
else
{
echo "Error: " . $mysqli->error;
}


$id = $_POST['id'];
$status = htmlentities($_POST['status'], ENT_QUOTES);
if (isset($_POST['submit'])){
//echo "<p> ID: ".$id." status: ".$status."</p>";
if ($status == 'approved' || $status=='rejected' || $status == 'pending'){

if ($stmt = $mysqli->prepare("UPDATE applicant_info SET status = ? WHERE id=?")){
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();
}
//header("Location: index.php");
//echo "<meta http-equiv='refresh' content='0'>";
}
}


$comment_id = $_POST['comment_id'];
$comment = $_POST['comment'];
if (isset($_POST['submit1'])){
//echo "<p> ID: ".$comment_id." Comment: ".$comment."</p>";
if ($stmt1 = $mysqli->prepare("UPDATE applicant_info SET comments = ?
WHERE id=?"))
{
$stmt1->bind_param("si", $comment, $comment_id);
$stmt1->execute();
$stmt1->close();
}
//header("Location: index.php");
//echo "<meta http-equiv='refresh' content='0'>";
}
}

// close database connection
$mysqli->close();

?>




</body>
</html>
