<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">  
    <link rel="apple-touch-icon" href="images/template/engage.png"/>
	<title>weathertrain:preferences</title>
	<meta name="author" content="Heath Westbrook">
	<!-- Date: 2012-07-18 -->
	
	<!-- stylesheets -->
	<link href="assets/css/style.css" rel="stylesheet">
	
	<!-- standard JS -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
  	<script src="assets/js/jquery.jeditable.mini.js"></script>

</head>
<body>
	
	<div id="form">

		<p>First Name: <span class="edit_1">Heath</span></p>
		<p>Last Name: <span class="edit_2">Westbrook</span></p>
		<p>Weather Station: <span class="edit_3">Button</span></p>
		<p>First Commute Stop: <span class="edit_4">Button</span></p>
		<p>Second Commute Stop: <span class="edit_5">Button</span></p>

	</div>
	
	<script>
		$(document).ready(function() {
			$('.edit_1').editable('save.php');
			$('.edit_2').editable('save.php');
		 });
	</script>
	
</body>
</html>
