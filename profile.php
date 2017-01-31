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

$formattedBirth = date("m/d/Y", strtotime($row->birth));

$formatted_date = date("m/d/Y", strtotime($row->date));


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





echo "<p><b>Dog(s) looking to adopt</b>:<br> " . $row->dog . "</p>";

echo "<form action='' method='POST'>
<input type='hidden' name='applicant_id' value='".$row->id."'>
<b>Dog image URL: </b><br>
<input type='text'size='30' name='dog_url' value='".$row->dog_url . "'>
<br/>
<input type='submit' name='submit1' value='Save URL'/>
</form>";

echo "<p><b>Applicant's Address: </b><br>" . $row->address . "</br>" .$row->city .  ", " . $row->state . " " . $row->zip . "<br>";
echo "<a href='https://www.google.com/maps/place/" . $row->address . " " . $row->city .  ", " . $row->state . " " . $row->zip . "' target='_blank'>Search on Google Maps</a></p>";


echo "<p><b>Phone:</b><br>(".substr($row->phone, 0, 3).") ".substr($row->phone, 3, 3)."-".substr($row->phone,6)."</p>";

echo "<p><b>Email Address:</b><br> " . $row->email . "<br><a href='http://mail.google.com/mail/?view=cm&fs=1&to=" .$row->email. "' target='_blank'>Compose using Gmail</p></a>";

echo "<p><b>Driver License Number:</b><br> " . $row->license . "</p>";

echo "<p><b>Date of Birth:</b><br> " . $formattedBirth . "</p>";

echo "<p><b>Occupation:</b><br> " . $row->occupation . "</p>";

echo "<p><b>Employer & Contact Info:</b><br> " . $row->employer . "</p>";

echo "<p><b>Work Address:</b><br>" . $row->work_address . "</br>" .$row->work_city .  ", " . $row->work_state . " " . $row->work_zip . "</p>";

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

$applicant_id = $_POST['applicant_id'];
$dog_url = $_POST['dog_url'];
if (isset($_POST['submit1'])){
//echo "<p> ID: ".$comment_id." Comment: ".$comment."</p>";
if ($stmt1 = $mysqli->prepare("UPDATE applicant_info SET dog_url = ?
WHERE id=?"))
{
$stmt1->bind_param("si", $dog_url, $applicant_id);
$stmt1->execute();
$stmt1->close();
}
//header("Location: index.php");
echo "<meta http-equiv='refresh' content='0'>";
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





}

// close database connection
$mysqli->close();

?>

</div>

</body>
</html>
