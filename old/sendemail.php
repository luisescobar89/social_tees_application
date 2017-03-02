<?php



// connect to the database
include('connect-db.php');

// Multiple recipients
$to = 'luis.scobr@gmail.com'; // note the comma

// Subject
$subject = 'Applications not Reviewed';

//if ($result = $mysqli->query("SELECT * FROM applicant_info ORDER BY ID"))

//if ($result = $mysqli->query("SELECT * FROM applicant_info WHERE DATE_SUB(CURDATE(),INTERVAL 20 DAY) >= submission_date"))
//{

// display records if there are records to display
//if ($result->num_rows > 0)
//{

$sql = "SELECT *
        FROM   applicant_info";
        

$result = mysql_query($sql);

//while ($row = $result->fetch_object())
while ($row = mysql_fetch_assoc($result)) 

{

//$dt = $row->submission_date;
//$date = new DateTime($dt);
//$now = new DateTime();
//$diff = $now->diff($date);

//if($diff->days > 20) {

// Message
    //    $message = "<tr>
    //    <td width=193 height=40 class=rightstyle>Voucher Number : </td>
  //      <td width=229 class=leftstyle> $row->firstname </td>
   //     <td width=234 class=leftstyle> $row->lastname </td>
     // </tr>";




echo "hello";


//echo "<p>".$row->firstname." Email Sent</p>";    


//}


// To send HTML mail, the Content-type header must be set
//$headers[] = 'MIME-Version: 1.0';
//$headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Mail it
//mail($to, $subject, $message, implode("\r\n", $headers));

}

//echo $message;





//}



//}


// close database connection
$mysqli->close();



?>