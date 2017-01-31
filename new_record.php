<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Add Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />
</head>
<body>


<div id="container1">

<h1>Add Record</h1>

<p><a href="index.php">View All</a> | <a href="search_record.php">Search</a> | <b>Add New Record</a></p>

<form action="" method="POST">
<b>First Name: </b>
<input type="text" name="firstname"><br/><br/>
<b>Last Name: </b>
<input type="text" name="lastname" value=""><br/><br/>
<b>Address: </b>
<input type="text" name="address"><br/><br>
<b>City: </b>
<input type="text" name="city"><br/><br>
<b>State: </b>
<input type="text" name="state"><br/><br>
<b>Zip: </b>
<input type="number" name="zip"><br/><br>
<b>Phone: </b>
<input type="number" name="phone"><br/><br>
<b>Email Address: </b>
<input type="text" name="email"><br/><br>
<b>Comments:</b><br>
<textarea rows='5' name='comment' cols='25'>
</textarea><br><br>
<b>Status: </b>
<select name='status'>
  <option value=''>Select...</option>
  <option value="approved">Approved</option>
  <option value="rejected">Rejected</option>
  <option value="pending">Pending</option>
</select><br><br>

<input type='submit' name='add_submit' value='Add Record'/>
</form>
<br/>
<br/>

<?php
// connect to the database
include('connect-db.php');

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$comment = $_POST['comment'];
$status = htmlentities($_POST['status'], ENT_QUOTES);
if (isset($_POST['add_submit'])){

//echo "<p> first name: ".$firstname. "lastname: ".$lastname. "address: " . $address . " city: " . $city . "state:" .$state. "zip: " . $zip . " phone: " .$phone. "email: " .$email. "comment: " . $comment. "Status: " . $status . "</p>";

// add the records to the database

if ($firstname == '' || $lastname == '' || $address == '' || $city == '' || $zip == '' || $phone == '' || $email == '' || $status == ''){

echo "<p style='color:red'>Please fill all required fields</p>";

}
else{

$sql = "INSERT INTO applicant_info (firstname, lastname, address, city, state, zip, phone, email, comments, status)
VALUES ('".$firstname."', '".$lastname."', '".$address."', '".$city."', '".$state."', '".$zip."', '".$phone."', '".$email."', '".$comment."', '".$status."')";

if ($mysqli->query($sql) === TRUE) {
    echo "<p style='color:green'>New record created successfully</p>";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}


}

}

// close database connection
$mysqli->close();

?>





</div>




</body>
</html>
