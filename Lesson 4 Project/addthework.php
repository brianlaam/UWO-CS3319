<?php
include 'connecttodb.php';

// Check if form fields exist before accessing them to prevent "Undefined array key" errors
$art = isset($_POST["work"]) ? $_POST["work"] : null;
$artist = isset($_POST["artist"]) ? $_POST["artist"] : null;
$theyear = isset($_POST["theyear"]) ? $_POST["theyear"] : null;
$whichmus = isset($_POST["whichmus"]) ? $_POST["whichmus"] : null;

// Validate that all required fields are filled
if (empty($art) || empty($artist) || empty($theyear) || empty($whichmus)) {
    die("Error: All fields must be filled.");
}

// Secure the input values to prevent SQL injection
$art = mysqli_real_escape_string($connection, $art);
$artist = mysqli_real_escape_string($connection, $artist);
$theyear = intval($theyear); // Ensuring year is an integer
$whichmus = intval($whichmus); // Ensuring museum ID is an integer

// Fix the SQL syntax (single quotes around strings, proper concatenation)
$query = "INSERT INTO workofart (artname, artist, year, whichmus) VALUES ('$art', '$artist', $theyear, $whichmus)";

// Execute query and handle errors properly
if (!mysqli_query($connection, $query)) {
    die("Error while trying to add new art: " . mysqli_error($connection));
} else {
    header('Location: museum.php');  // Redirect back to museum page once completed
    exit;
}
?>
