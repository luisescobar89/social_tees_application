<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>View Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body{
font-family: helvetica;
}

</style>
</head>
<body>

<h1>View Records</h1>

<p><b>View All</b> | <a href="search_record.php">Search</a> | <a href="new_record.php">Add New Record</a></p>

<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<b>Order By: </b> <select name='order'>
  <option value=''>Select...</option>
  <option value='id'>ID</option>
  <option value='firstname'>First Name</option>
  <option value='lastname'>Last Name</option>
</select>

<input type='submit' name='orderby_submit' value='Submit'/>
</form><br>

<?php
// connect to the database
include('connect-db.php');

$order_selected = htmlentities($_POST['order'], ENT_QUOTES);

// get the records from the database

if ($result = $mysqli->query("SELECT * FROM applicant_info ORDER BY id"))
{
// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo "<table border='1' cellpadding='8'>";

// set table headers
echo "<tr> <th>ID</th> <th>Name</th>  <th>Address</th> <th>Phone</th> <th>Email Address</th> <th>Date</th> <th>Comments</th> <th>Status</th> <th>Set Status</th> <th></th> </tr>";

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
echo "<td><a href='profile.php?id=" . $row->id . "'>".$row->firstname . " " . $row->lastname . "</a></td>";
//echo "<td>" . $row->firstname . " " . $row->lastname . "</td>";
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
echo "<meta http-equiv='refresh' content='0'>";
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
echo "<meta http-equiv='refresh' content='0'>";
}


// close database connection
$mysqli->close();

?>










</body>
</html>
