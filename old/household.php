<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Household</title>
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

echo "<p><b>Type of residence:</b><br> " . $row->residence_type . "</p>";

echo "<p><b>Residence type if other selected:</b><br> " . $row->residence_type_description . "</p>";

echo "<p><b>Current residence time lived:</b><br> " . $row->current_residence_time . "</p>";

echo "<p><b>Previous residence address and time lived:</b><br> " . $row->previous_residence_address . "</p>";

echo "<p><b>Current Residence rent or own:</b><br> " . $row->residence_ownership . "</p>";

if ($row->landlord_info == ''){
echo "<p><b>If rental, landlord name and phone:</b><br>No info provided</p>";
}
else{
echo "<p><b>If rental, landlord name and phone:</b><br> " . $row->landlord_info . "</p>";
}

echo "<p><b>Access to backyard:</b><br> " . $row->backyard . "</p>";

echo "<p><b>Enclosed backyard:</b><br> " . $row->enclosed_backyard . "</p>";

echo "<p><b>Fence height:</b><br> " . $row->backyard_fence . "</p>";

echo "<p><b>Household members:</b><br> " . $row->roommates . "</p>";

echo "<p><b>Others spending time with dog:</b><br> " . $row->sharing_dog . "</p>";

echo "<p><b>Household members with allergies:</b><br> " . $row->allergies . "</p>";

echo "<p><b>Current pets:</b><br> " . $row->household_pets . "</p>";

echo "<p><b>Type of food fed to pets:</b><br> " . $row->food_type . "</p>";

echo "<p><b>Object to visit before or after adoption:</b><br> " . $row->visit_object . "</p>";

echo "<p><b>Person who will take care of dog in case of illness:</b><br> " . $row->illness_contact . "</p>";

echo "<p><b>Able to financially afford dog:</b><br> " . $row->financial . "</p>";

echo "<p><b>Willing to pay adoption fee:</b><br> " . $row->adoption_fee . "</p>";



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
