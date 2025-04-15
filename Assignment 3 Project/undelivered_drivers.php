<?php
// undelivered_drivers.php
include('config.php');
$query = "SELECT driverid, firstname, lastname FROM driver 
          WHERE driverid NOT IN (SELECT DISTINCT driverid FROM cusorder WHERE driverid IS NOT NULL)";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Drivers with No Deliveries</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Drivers with No Deliveries</h1>
    <?php
    if($result->num_rows > 0){
        echo "<table>";
        echo "<tr><th>Driver ID</th><th>First Name</th><th>Last Name</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row['driverid']."</td>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "All drivers have made deliveries.";
    }
    ?>
</div>
</body>
</html>
