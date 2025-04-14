<!DOCTYPE html>
<html>
<head>
<title>Worldwide Museums</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="museum.css">
</head>
<body>
<?php
include "connecttodb.php";
?>
<h1>Museums of the World </h1>
Select your museum:
<form  action="showartwork.php" method="post">
<select name="pickamuseum">
  <option value="0">Select Here</option>
<?php
include "getmuseum.php";
?>
</select>
<hr>
<input type="submit" value="See Art Works from selected
museum">
</form>
<hr>
<img src="http://www.csd.uwo.ca/~lreid/blendedcs3319/flippedclassroom/four/kids.png" width="216" height="260">
</body>
</html>
