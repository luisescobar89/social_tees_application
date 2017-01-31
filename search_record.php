<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Search Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />
</head>
<body>

<div id="container1">

<h1>Search Dog Adoption Application Records</h1>

<p><a href="index.php">View All</a> | <b>Search</b> | <a href="new_record.php">Add New Record</a></p>

<form action="" method="POST">
<b>Search by Last Name: </b>
<input type="text" name="applicant_name"><br/><br/>
<b>Search by ID: </b>
<input type="number" name="applicant_id" value=""><br/><br/>
<b>Search by Processed Date: </b>
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

if($applicant_date == ''){
   $new_applicant_date = "1950-1-1";

}
else{
   $new_applicant_date = $applicant_date;
}


//echo "<p> ID: ".$applicant_id." name: ".$new_applicant_name. " date: " . $new_applicant_date . "</p>";

if ($result = $mysqli->query("SELECT * FROM applicant_info WHERE id='".$applicant_id."' OR lastname LIKE '%".$new_applicant_name."%' OR DATE='".$new_applicant_date."'"))
{


// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo "<table id='applicants'>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Address</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Status</th>
    <th>Processing Date</th>
  </tr>";

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
//echo "<tr style='background-color:".$row_color.";'>";
echo "<tr>";
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

echo "<td>" . $row->status . "</td>";

if($row->status == "approved" || $row->status == "rejected"){
echo "<td>" . $newDate . "</td>";

}
else{
echo "<td></td>";
}

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

</div>


</body>
</html>
