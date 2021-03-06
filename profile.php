<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Applicant Profile</title>
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

$submission_date = date("m/d/Y", strtotime($row->submission_date));

$formattedBirth = date("m/d/Y", strtotime($row->birth));

$formatted_date = date("m/d/Y", strtotime($row->date));

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

echo "<a class='app_nav' id='active' href='profile.php?id=" . $id . "'>View Profile</a><a class='app_nav' href='dog_parameters.php?id=" . $id . "'>Dog Parameters</a></li><a class='app_nav' href='dog_history.php?id=" . $id . "'>Dog Ownership History</a><a class='app_nav' href='household.php?id=" . $id . "'>Household</a><a class='app_nav' href='comments.php?id=" . $id . "&email_sent=".$row->email_sent ."'>Comments & Approvals</a><br/><br>";

echo "</div>";

echo "<div id='container3'>";

echo "<div class='title'>Contact Info</div>";

echo "<p><b>Phone:</b><br>(".substr($row->phone, 0, 3).") ".substr($row->phone, 3, 3)."-".substr($row->phone,6)."</p>";

echo "<p><b>Email Address:</b><br> " . $row->email . "<br></p>";







echo "<div class='title'>Personal Info</div>";

echo "<p><b>Driver License Number:</b><br> " . $row->license . "</p>";

echo "<p><b>Date of Birth:</b><br> " . $formattedBirth . " (" . $age . " years old)</p>";

echo "<p><b>Occupation:</b><br> " . $row->occupation . "</p>";

echo "<p><b>Employer & Contact Info:</b><br> " . $row->employer . "</p>";

echo "<p><b>Work Address:</b><br>" . $row->work_address . "</br>" .$row->work_city .  ", " . $row->work_state . " " . $row->work_zip . "</p>";

echo "</div>";

echo "<div id='container5'>";

echo "<div class='title'>Applicant's Address: <span style='font-weight:normal;' id=''>" . $merged_address . "</span></div><br>";


echo
"<iframe
  width='100%'
  height='450'
  frameborder='0' style='border:0'
  src='https://www.google.com/maps/embed/v1/place?key=AIzaSyA6sQwbP241sxfVSDstIN5sfK3zQKRhSsU
    &q=" . $merged_address ."' allowfullscreen>
</iframe>";




echo "</div>";


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

}

// close database connection
$mysqli->close();

?>

</div>

</body>
</html>
