<?php
$dbhost = "localhost";
$dbuser = $_POST['theuser'];  // Username entered by the user
$dbpass = $_POST['pwd'];  // Password entered by the user
$dbname = "flipped4db";

$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()) {
    die("Database connection failed checking user");
}
else {
header('Location: museum.php');
}
exit;
?>