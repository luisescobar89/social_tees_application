<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Dog History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />



</head>
<body>

<ul>
<li><a href="index.php">HOME</a></li>
<li><a href="search_record.php">SEARCH</a></li>
<li><a href="search_record.php">ADD APPLICANT</a></li>
</ul>

<div id="container1">




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
$submission_date = date("m/d/Y", strtotime($row->submission_date));

echo "<h2 style='color:black;'>" . $row->firstname . " " . $row->lastname . "   <span style='font-size:0.8em; padding: 7px; margin-left:30px;  background-color:" . $row_color . "'>" . ucfirst($row->status) .  "</span></h2>";

echo "<p><b>Application Submitted on: </b>" . $submission_date . "<span style='margin-left:40px';><b>Dog(s) looking to adopt: </b> " . $row->dog . "</span></p>";

echo "<hr>";

echo "<div id='container2'>";

echo "<a class='app_nav' href='profile.php?id=" . $id . "'>View Profile</a><a class='app_nav' href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a></li><a class='app_nav'  id='active' href='dog_history.php?id=" . $id . "'>Dog Ownership History</a><a class='app_nav' href='household.php?id=" . $id . "'>Household</a><a class='app_nav' href='comments.php?id=" . $id . "&email_sent=".$row->email_sent ."'>Comments & Approvals</a><br/><br>";

echo "</div>";

echo "<div id='container3'>";

echo "<div class='title'>History</div>";

echo "<p><b>Currently own a dog or previously owned dogs:</b> " . $row->dog_history . "</p>";

echo "<p><b>Likes about having a dog:</b><br> " . $row->dog_appeal . "</p>";

echo "<p><b>References:</b><br> " . $row->pet_references . "</p>";

echo "<p><b>Current Veterinarian:</b><br> " . $row->veterinarian . "</p>";

echo "<p><b>Planned Veterinarian:</b><br> " . $row->planned_veterinarian . "</p>";

echo "<p><b>Surrendered animal to shelter in past:</b><br> " . $row->pet_surrender . "</p>";

if ($row->pet_surrender_explanation == ''){

echo "<p><b>Animal Surrender Explanation:</b><br>No Response Provided</p>";
}
else{

echo "<p><b>Animal Surrender Explanation:</b><br> " . $row->pet_surrender_explanation . "</p>";

echo "</div>";



}





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


</body>
</html>
