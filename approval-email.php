<html>
<head>
<title>Application Approved</title>
</head>
</body>

<h3>The  applicant info has been emailed with an status of approved</h3>

<?php



// connect to the database
include('connect-db.php');

// Multiple recipients
$to = 'luis.scobr@gmail.com'; // note the comma

// Subject
$subject = 'Applications not Reviewed';

$id = $_GET['id'];

if (isset($_GET['id'])){

$query = "SELECT * FROM applicant_info WHERE id=" . $id . "";

$result = $mysqli->query($query);




while($row = $result->fetch_array())
{
$rows[] = $row;

}


foreach($rows as $row)
{
$approval_date = date("m/d/Y", strtotime($row['date']));

$arr[] = "<tr><td>" .  $row['id'] . "</td><td>" . $row['firstname'] . " " . $row['lastname'] . "</td><td>" . $row['dog'] . "</td><td>" . $approval_date . "</td></tr>";

}

$message1 = "<b>The following application has been approved:</b><br><br>
<table border ='1' cellpadding='7'>
	<tr>
		<th align='left'>ID</th>
		<th align='left'>Name</th>
		<th align='left'>Dog Adopted</th>
                <th align-'left'> Approved on</th>
	</tr>


";
$message2 = implode($arr);
$message = $message1 . $message2;
//echo $message;

// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Mail it
mail($to, $subject, $message, implode("\r\n", $headers));

echo "<a href='profile.php?id=" . $id . "'>Back to Profile</a>";

/* free result set */
$result->close();


/* close connection */
$mysqli->close();

}// close if get id condition

?>

</body>
</html>