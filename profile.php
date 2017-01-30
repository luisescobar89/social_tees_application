<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Applicant Profile</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />



</head>
<body>

<a style="margin-left:200px;" href="index.php">Home</a>

<div class="container">

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


echo "<h2 style='background-color:".$row_color."'>Applicant Profile: " . $row->firstname . " " . $row->lastname ."</h2>";

echo "<a href='profile.php?id=" . $id . "'>View Profile</a> | <a href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a> | <a href='dog_history.php?id=" . $id . "'>Dog Ownership History</a> | <a href='household.php?id=" . $id . "'>Household</a> | <a href='comments.php?id=" . $id . "'>Comments</a><br/><br>";

echo "<hr>";

if($row->dog_url == ''){
echo "<p>No image  url provided</p>";
}
else{
echo "<img class='dog_image' src='" . $row->dog_url . "'>";
}

echo "<p><b>Approval Status: </b> " . $row->status . "</p>";

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
echo "";
}

echo "<p><b>Dog(s) looking to adopt</b>:<br> " . $row->dog . "</p>";

echo "<form action='' method='POST'>
<input type='hidden' name='applicant_id' value='".$row->id."'>
<b>Dog image URL: </b><br>
<textarea rows='2' name='dog_url' cols='30'>
".$row->dog_url . "
</textarea>
<br/>
<input type='submit' name='submit1' value='Save URL'/>
</form>";

echo "<p><b>Applicant's Address: </b><br>" . $row->address . "</br>" .$row->city .  ", " . $row->state . " " . $row->zip . "<br>";
echo "<a href='https://www.google.com/maps/place/" . $row->address . " " . $row->city .  ", " . $row->state . " " . $row->zip . "' target='_blank'>Search on Google Maps</a></p>";


echo "<p><b>Phone:</b><br>(".substr($row->phone, 0, 3).") ".substr($row->phone, 3, 3)."-".substr($row->phone,6)."</p>";

echo "<p><b>Email Address:</b><br> " . $row->email . "</p>";

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
$current_date = date("Y/m/d");
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
