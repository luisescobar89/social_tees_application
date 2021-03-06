<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Dog Parameters</title>
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

$formattedBirth = date("m/d/Y", strtotime($row->birth));

echo "<h2 style='background-color:".$row_color."; padding:10px;'>Applicant Profile: " . $row->firstname . " " . $row->lastname ."</h2>";

echo "<a href='profile.php?id=" . $id . "'>View Profile</a> | <a href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a> | <a href='dog_history.php?id=" . $id . "'>Dog Ownership History</a> | <a href='household.php?id=" . $id . "'>Household</a> | <a href='comments.php?id=" . $id . "&email_sent=".$row->email_sent ."'>Comments & Approvals</a><br/><br>";

echo "<hr>";

if($row->dog_url == ''){
echo "";
}
else{
echo "<img class='dog_image' src='" . $row->dog_url . "'>";
}

echo "<p style='color:blue; font-size:1.5em;';><b>Approval Status: </b><span style='color:".$row_color.";'> " . $row->status . "</p>";

echo "<p><b>Looking for in a dog:</b><br> " . $row->dog_parameters . "</p>";

echo "<p><b>Primary caregiver:</b><br> " . $row->dog_caregiver . "</p>";

echo "<p><b>Household activity level:</b><br> " . $row->activity_level . "</p>";

echo "<p><b>How will dog relieve itself and get exercise:</b><br> " . $row->dog_exercise . "</p>";

echo "<p><b>Hours outside house:</b><br> " . $row->hours_outside . "</p>";

echo "<p><b>Hours dog will be left alone:</b><br> " . $row->hours_alone . "</p>";

echo "<p><b>Often traveler, who will take care of dog during travel:</b><br> " . $row->travel . "</p>";

echo "<p><b>Dog stay during day:</b><br> " . $row->daystay . "</p>";

echo "<p><b>Dog stay during night:</b><br> " . $row->nightstay . "</p>";

echo "<p><b>Type of training provided for dog:</b><br> " . $row->dog_training . "</p>";



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





$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
if (isset($_POST['submit'])){
echo "<img src= ".$image."</img>";
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
