<html>
<head>
<title></title>
</head>


<body>

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




/* Title : Ajax Pagination with jQuery & PHP
Example URL : http://www.sanwebe.com/2013/03/ajax-pagination-with-jquery-php */

//continue only if $_POST is set and it is a Ajax request
if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	
	include("config.inc.php");  //include config file
	//Get page number from Ajax POST
	if(isset($_POST["page"])){
		$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
		if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
	}else{
		$page_number = 1; //if there's no page number, set it to 1
	}

	$item_per_page 		= 5; //item to display per page



	
	//get total number of records from database for pagination
	$results = $mysqli->query("SELECT COUNT(*) FROM applicant_info");
	$get_total_rows = $results->fetch_row(); //hold total records in variable
	//break records into pages
	$total_pages = ceil($get_total_rows[0]/$item_per_page);
	
	//get starting position to fetch the records
	$page_position = (($page_number-1) * $item_per_page);


	//Limit our results within a specified range. 
	$results = $mysqli->query("SELECT * FROM applicant_info ORDER BY id LIMIT $page_position, $item_per_page");
	//$results->execute(); //Execute prepared Query
	//$results->bind_result($id, $firstname, $description); //bind variables to prepared statement
	
	//Start Building Table

	echo "<table id='applicants'>
	<thead>
	 <tr>
  	   <th>ID</th>
 	   <th>Name</th>
 	   <th>Address</th>
 	   <th>Phone</th>
	   <th>Email</th>
  	   <th>Status</th>
  	   <th>Processing Date</th>
  	   <th>Email Sent</th>
	   
	 </tr>
	 </thead>
	 <tbody>";

	//Display records fetched from database.
	echo '<ul class="contents">';
	while($row = $results->fetch_array()){ //fetch values
//change date format
$originalDate = $row['date'];
$newDate = date("m/d/Y", strtotime($originalDate));


echo "<tr data-href='profile.php'>";
echo "<td>" . $row['id'] . "</td>";
echo "<td><a href='profile.php?id=" . $row['id'] . "'>".$row['firstname'] . " " . $row['lastname'] . "</a></td>";

echo "<td>" . $row['address'] . "</br>" .$row['city'] .  ", " . $row['state'] . " " . $row['zip'] . "</td>";

echo "<td>(".substr($row['phone'], 0, 3).") ".substr($row['phone'], 3, 3)."-".substr($row['phone'],6)."</td>";
echo "<td>" . $row['email'] . "</td>";

echo "<td>" . $row['status'] . "</td>";

if($row['status'] == "approved" || $row['status'] == "rejected"){
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
	}
	echo '</tbody></table>';

	echo '<div align="center">';
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	echo paginate_function($item_per_page, $page_number, $get_total_rows[0], $total_pages);
	echo '</div>';

$order_selected = htmlentities($_POST['order'], ENT_QUOTES);

echo $order_selected;

echo "hello";

	
	exit;
}
################ pagination function #########################################
function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 3; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 1){
			$previous_link = ($previous==0)? 1: $previous;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active">'.$current_page.'</li>';
        }else{ //regular current link
            $pagination .= '<li class="active">'.$current_page.'</li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = ($i > $total_pages) ? $total_pages : $i;
                $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
                $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
    return $pagination; //return pagination links




}



?>




</body>
</html>