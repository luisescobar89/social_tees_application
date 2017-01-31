<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Applicant Profile</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />



</head>
<body>


<div id="container1">

<a href="index.php">Home</a><br><br>
<?php

// connect to the database
include('connect-db.php');

$id = $_GET['id'];
if (isset($_GET['id'])){

//echo "<p> ID: ".$id."</p>";

// get the records from the database


if ($result = $mysqli->query("SELECT * FROM applicant_info WHERE id='".$id."'"))
{


// display records if there are records to display
if ($result->num_rows > 0)
{


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

$formatted_date = date("m/d/Y", strtotime($row->date));

$formattedBirth = date("m/d/Y", strtotime($row->birth));

echo "<h2 style='background-color:".$row_color."; padding:10px;'>Applicant Profile: " . $row->firstname . " " . $row->lastname ."</h2>";

echo "<a href='profile.php?id=" . $id . "'>View Profile</a> | <a href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a> | <a href='dog_history.php?id=" . $id . "'>Dog Ownership History</a> | <a href='household.php?id=" . $id . "'>Household</a> | <a href='comments.php?id=" . $id . "'>Comments & Approvals</a><br/><br>";

echo "<hr>";


echo "<p style='color:blue; font-size:1.5em;';><b>Approval Status: </b><span style='color:".$row_color.";'> " . $row->status . "</p>";

echo "<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<b>Set Approval Status: </b> <select name='status'>
  <option value=''>Select...</option>
  <option value='approved'>Approved</option>
  <option value='rejected'>Rejected</option>
  <option value='pending'>Pending</option>
</select>

<input type='submit' name='submit' value='Submit'/>
</form>";

if ($row->status == 'rejected'){
echo "<p><b>Application rejected on: </b>". $formatted_date . "</p>";
}
elseif ($row->status == 'approved'){
echo "<p><b>Application approved on: </b>" . $formatted_date . "</p>";
}
else{
echo "<br>";
}

echo "<form action='' method='POST'>
<input type='hidden' name='comment_id' value='".$row->id."'>
<b>Comments: </b><br><br>
<textarea rows='10' name='comment' cols='40'>
".$row->comments."
</textarea>
<br/>
<input type='submit' name='submit1' value='Save Comment'/>
</form>";
}


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
if ($status == 'approved' || $status=='rejected'){
$current_date = date("Y/m/d");
}
elseif($status == 'pending'){
$current_date == '';
}
//echo $current_date;
if ($status == 'approved' || $status=='rejected' || $status == 'pending')
{
if ($stmt = $mysqli->prepare("UPDATE applicant_info SET status = ?, date = ? WHERE id=?")){
$stmt->bind_param("ssi", $status, $current_date, $id);
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
}



// close database connection
$mysqli->close();

?>

</div>

</body>
</html>
