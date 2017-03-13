<?php



// connect to the database
include('connect-db.php');

// Multiple recipients
$to = 'luis.scobr@gmail.com'; // note the comma

// Subject
$subject = 'Approved applications not emailed';

$query = "SELECT * FROM applicant_info WHERE DATE_SUB(CURDATE(),INTERVAL 3 DAY) >= submission_date AND status = 'approved' ORDER BY ID";

$result = $mysqli->query($query);




while($row = $result->fetch_array())
{
$rows[] = $row;

}


foreach($rows as $row)
{
$submission_date = date("m/d/Y", strtotime($row['submission_date']));

$arr[] = "<tr><td>" .  $row['id'] . "</td><td>" . $row['firstname'] . " " . $row['lastname'] . "</td><td>" . $submission_date . "</td></tr>";

}

$message1 = "<b>The following approved applications have not been emailedd in more than 3 days:</b><br><br>
<table border ='1' cellpadding='7'>
	<tr>
		<th align='left'>ID</th>
		<th align='left'>Name</th>
		<th align='left'>Submitted on</th>
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

/* free result set */
$result->close();


/* close connection */
$mysqli->close();
?>