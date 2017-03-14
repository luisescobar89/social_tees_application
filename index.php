<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>View Records</title>
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

<h1>Dog Adoption Application Records</h1>


<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<b>Order By: </b> <select name='order'>
  <option value=''>Select...</option>
  <option value='id'>ID</option>
  <option value='firstname'>First Name</option>
  <option value='lastname'>Last Name</option>
  <option value='date DESC'>Processing Date</option>
  <option value='status'>Application Status</option>
</select>

<input type='submit' name='orderby_submit' value='Submit'/>
</form><br>

<?php
// connect to the database
include('connect-db.php');

$order_selected = htmlentities($_POST['order'], ENT_QUOTES);



if ($order_selected == ''){
$order_selected = 'id';
}


if ($result = $mysqli->query("SELECT * FROM applicant_info ORDER BY ".$order_selected.""))
{

// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo "<table id='applicants'>
  <tr>
    <th align='center'>ID</th>
    <th align='center'>Name</th>
    <th align='center'>Address</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Dog's Name</th>
    <th>Submission Date</th>
    <th>Status</th>
    <th>Processing Date</th>
    <th>Email Sent</th>
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
$submission_date = date("m/d/Y", strtotime($row->submission_date));


// set up a row for each record
//echo "<tr style='background-color:".$row_color.";'>";
echo "<tr data-href='profile.php'>";
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
echo "<td>" . $row->dog . "</td>";
echo "<td>" . $submission_date . "</td>";
echo "<td>" . $row->status . "</td>";

if($row->status == "approved" || $row->status == "rejected"){
echo "<td>" . $newDate . "</td>";

}
else{
echo "<td></td>";
}

if($row->email_sent == "true"){
echo "<td>Yes</td>";


}
else{
echo "<td>No</td>";
}

echo "</tr>";

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







// close database connection
$mysqli->close();

?>




</div>





</body>
</html>
