<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Dog History</title>
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

echo "<a href='profile.php?id=" . $id . "'>View Profile</a> | <a href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a> | <a href='dog_history.php?id=" . $id . "'>Dog Ownership History</a> | <a href='household.php?id=" . $id . "'>Household</a> | <a href='comments.php?id=" . $id . "'>Comments & Approvals</a><br/><br>";

echo "<hr>";

if($row->dog_url == ''){
echo "";
}
else{
echo "<img class='dog_image' src='" . $row->dog_url . "'>";
}

echo "<p style='color:blue; font-size:1.5em;';><b>Approval Status: </b><span style='color:".$row_color.";'> " . $row->status . "</p>";


echo "<p><b>Currently own a dog or previously owned dogs:</b><br> " . $row->dog_history . "</p>";

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
