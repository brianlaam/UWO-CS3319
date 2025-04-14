<!DOCTYPE html>
<html>
<head>
    <title>Worldwide Museums – The Art</title>
    <link rel="stylesheet" type="text/css" href="museum.css">
    <link href="https://fonts.googleapis.com/css?family=Mali" rel="stylesheet">
</head>
<body>
<?php
    include "connecttodb.php";

    // Get the selected museum ID from the form submission
    $whichMus = $_POST["pickamuseum"];

    // Query to get the selected museum's name
    $querymus = "SELECT musname FROM museum WHERE musID=" . intval($whichMus);
    $result = mysqli_query($connection, $querymus);

    if (!$result) {
        die("Database querymus on museum failed.");
    }

    // Fetch museum name
    $row = mysqli_fetch_assoc($result);
    echo "<h1>Art Works at " . $row["musname"] . " include </h1>";

    // Query to get artworks belonging to the selected museum
    $queryart = "SELECT artname, artist FROM workofart WHERE whichmus=" . intval($whichMus);

    // Execute the query
    $result = mysqli_query($connection, $queryart);

    if (!$result) {
        die("Database queryart on museum failed.");
    }

    // Start unordered list
    echo "<ul>";

    // Fetch and display each artwork and artist
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row["artname"] . " by " . $row["artist"] . "</li>";
    }

    echo "</ul>";

    // Free the result set
    mysqli_free_result($result);
?>
<a href="javascript:history. go(-1)">Go back to the Main Museum Page</a>
</body>
</html>
