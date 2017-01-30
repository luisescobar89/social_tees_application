<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Search Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body{
font-family: helvetica;
}

</style>
</head>
<body>

<h1>Search Records</h1>

<p><a href="index.php">View All</a> | <b>Search</b> | <a href="new_record.php">Add New Record</a></p>

<form action="search.php" method="GET">
<b>Search by Last Name: </b>
<input type="text" name="applicant_name"><br/><br/>
<b>Search by ID: </b>
<input type="number" name="applicant_id" value=""><br/><br/>
<b>Search by Date: </b>
<input type="date" name="date"><br/><br>

<input type='submit' name='search_submit' value='Search'/>
</form>
<br/>
<br/>






</body>
</html>
