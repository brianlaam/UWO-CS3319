<?php
$query = "SELECT * FROM museum";  // Correct SQL statement to fetch all data from museum table

$result = mysqli_query($connection, $query);
if (!$result) {
    die("Database query failed.");
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row["musID"] . "'>" . $row["musname"] . "</option>";

}

mysqli_free_result($result);
?>
