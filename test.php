<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>toggle demo</title>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
 
<button id="date_button">Toggle</button>
<p class="date_sort">Hello</p>
<p class="date_sort" style="display: none">Good Bye</p>
 
<script>
$( "#date_button" ).click(function() {
  $( ".date_sort" ).toggle();
});
</script>
 
</body>
</html>
