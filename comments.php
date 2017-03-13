<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Comments & Approvals</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#approval_table_link").click(function(){
        $("#approval_table").toggle();
    });
});
</script>




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

$email_sent = $_GET['email_sent'];
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

} elseif($row->status == "pending"){
  $row_color = "grey";

}

//change date format
$originalDate = $row->date;
$newDate = date("m/d/Y", strtotime($originalDate));

$formatted_date = date("m/d/Y H:i:s", strtotime($row->date));

$formattedBirth = date("m/d/Y", strtotime($row->birth));
$submission_date = date("m/d/Y", strtotime($row->submission_date));

  $birthDate = $formattedBirth;
  //explode the date to get month, day and year
  $birthDate = explode("/", $birthDate);
  //get age from date or birthdate
  $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
    ? ((date("Y") - $birthDate[2]) - 1)
    : (date("Y") - $birthDate[2]));
  //echo "Age is:" . $age;

$merged_address = $row->address . " " .$row->city .  ", " . $row->state . " " . $row->zip;

echo "<h2 style='color:black;'>" . $row->firstname . " " . $row->lastname . "   <span style='font-size:0.8em; padding: 7px; margin-left:30px;  background-color:" . $row_color . "'>" . ucfirst($row->status) .  "</span></h2>";

echo "<p><b>Application Submitted on: </b>" . $submission_date . "<span style='margin-left:40px';><b>Dog(s) looking to adopt: </b> " . $row->dog . "</span></p>";

echo "<hr>";

echo "<div id='container2'>";

echo "<a class='app_nav'  href='profile.php?id=" . $id . "'>View Profile</a><a class='app_nav' href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a></li><a class='app_nav' href='dog_history.php?id=" . $id . "'>Dog Ownership History</a><a class='app_nav' href='household.php?id=" . $id . "'>Household</a><a class='app_nav' id='active' href='comments.php?id=" . $id . "&email_sent=".$row->email_sent ."'>Comments & Approvals</a><br/><br>";

echo "</div>";

if($row->status == 'approved'){
//echo "Application was approved and email already sent";
$set_dropdown = "disabled";
}
else{
$set_dropdown = "";
}

echo "<div id='container8'>";


echo "<p style='color:blue; font-size:1.5em;';><b>Approval Status: </b><span style='color:".$row_color.";'> " . $row->status . "</p>";

echo "<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<b>Set Approval Status: </b> <select name='status'>
  <option value=''>Select...</option>
  <option value='approved'". $set_dropdown . ">Approved</option>
  <option value='rejected'>Rejected</option>
  <option value='pending'>Pending</option>
</select>

<input type='submit' name='submit' value='Submit'/>
</form>";

echo "<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<b>Email Status: </b> <select name='email_status'>
  <option value=''>Select...</option>
  <option value='true'>Sent</option>
  <option value='false'>Not Sent</option>
</select>

<input type='submit' name='email_submit' value='Submit'/>
</form>";

if ($row->status == 'rejected'){
echo "<p><b>Application rejected on: </b>". $formatted_date . "</p>";
}
elseif ($row->status == 'approved' && $row->email_sent == 'true'){
echo "<p><b>Application approved and emailed on: </b>" . $formatted_date . "</p>";
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

echo "<p>To send approval confirmation using Gmail click <a href='http://mail.google.com/mail/?view=cm&fs=1&to=&su=Application Approved&body=Name of Dog: ".$row->dog."%0D%0AEmail: " .$row->email."%0D%0AName of Applicant: ". $row->firstname . " " . $row->lastname . "%0D%0ARecord ID: " . $row->id . "%0D%0AAge: " . $age . "%0D%0AOccupation: " .$row->occupation ."%0D%0ALiving: ".$merged_address."%0D%0ALiving w/applicant: " . $row->dog_caregiver."%0D%0AAllergies: " .$row->allergies."%0D%0AHours Dog Alone: " .$row->hours_alone . "%0D%0AComments: " .$row->comments. "' target='_blank'>here</p></a>";

echo "<table border='1' cellpadding='5' width='100%' id='approval_table' style='display:none;'>
  <tr>
    <th align='left'>Name of Dog</th>
    <th>Email</th>
    <th>Name of Applicant</th>
    <th>ID</th>
    <th>Age</th>
    <th>Occupation</th>
    <th>Living</th>
    <th>Living w/ applicant</th>
    <th>Allergies</th>
    <th>Hrs Dog Alone</th>
    <th>Comments</th>
  </tr>";



echo "<tr>";
echo "<td>" . $row->dog . "</td>";
echo "<td>" . $row->email . "</td>";
echo "<td>" . $row->firstname . " " . $row->lastname . "</td>";
echo "<td>" . $row->id . "</td>";
echo "<td>" . $age . "</td>";
echo "<td>" . $row->occupation . "</td>";
echo "<td>" . $row->address . "</br>" .$row->city .  ", " . $row->state . " " . $row->zip . "</td>";
echo "<td>" . $row->dog_caregiver . "</td>";
echo "<td>" . $row->allergies . "</td>";
echo "<td>" . $row->hours_alone . "</td>";
echo "<td>" . $row->comments . "</td>";


echo "</tr>";

echo "<a id='approval_table_link' href='#'>Show/Hide Approval Table</a><br><br>";

}

echo "</table>";

echo "</div>";

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
if ($status == 'approved'){
$current_date = date("Y/m/d H:i:s");
//$email_sent="true";

}
elseif($status=='rejected'){
$current_date = date("Y/m/d H:i:s");
//$email_sent="false";
}
elseif($status == 'pending'){
$current_date == '';
//$email_sent="false";
}
//echo $current_date;
if ( $status=='rejected' || $status == 'pending')
{
if ($stmt = $mysqli->prepare("UPDATE applicant_info SET status = ?, date = ?, email_sent = ? WHERE id=?")){
$stmt->bind_param("sssi", $status, $current_date, $email_sent, $id);
$stmt->execute();
$stmt->close();
}
header("Location: comments.php?id=" . $id . "&email_sent=". $email_sent ."");
//header("Location: approval-email.php?id=" . $id . "");
//header("Location: comments.php?id=" . $id. "");
//echo "<meta http-equiv='refresh' content='0'>";
}

if ($status == 'approved'){
if ($stmt = $mysqli->prepare("UPDATE applicant_info SET status = ?, date = ?, email_sent = ? WHERE id=?")){
$stmt->bind_param("sssi", $status, $current_date, $email_sent, $id);
$stmt->execute();
$stmt->close();
}
}

//header("Location: approval-email.php?id=" . $id . "");
//header("Location: comments.php?id=" . $id . "&statust=". $status ."");
echo "<meta http-equiv='refresh' content='0'>";

}

$email_status = $_POST['email_status'];
if (isset($_POST['email_submit'])){
//echo "<p> ID: ".$comment_id." Comment: ".$comment."</p>";
if ($stmt1 = $mysqli->prepare("UPDATE applicant_info SET email_sent = ?
WHERE id=?"))
{
$stmt1->bind_param("si", $email_status, $id);
$stmt1->execute();
$stmt1->close();
}
//header("Location: comments.php");
echo "<meta http-equiv='refresh' content='0'>";
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
header("Location: index.php");
//echo "<meta http-equiv='refresh' content='0'>";
}
}





// close database connection
$mysqli->close();

?>

</div>

</body>
</html>
