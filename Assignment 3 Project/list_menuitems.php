<?php
// list_menuitems.php
include('config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>List Menu Items</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Menu Items</h1>
    <form method="get" action="list_menuitems.php">
        <label>Order By:</label>
        <input type="radio" name="orderby" value="dishname" <?php if(isset($_GET['orderby']) && $_GET['orderby'] == 'dishname') echo 'checked'; ?>> Dish Name
        <input type="radio" name="orderby" value="price" <?php if(isset($_GET['orderby']) && $_GET['orderby'] == 'price') echo 'checked'; ?>> Price
        <br><br>
        <label>Order Direction:</label>
        <input type="radio" name="direction" value="ASC" <?php if(!isset($_GET['direction']) || $_GET['direction'] == 'ASC') echo 'checked'; ?>> Ascending
        <input type="radio" name="direction" value="DESC" <?php if(isset($_GET['direction']) && $_GET['direction'] == 'DESC') echo 'checked'; ?>> Descending
        <br><br>
        <input type="submit" value="Sort">
    </form>
    <br>
    <?php
    // Default order field and direction.
    $orderBy = "dishname";
    if(isset($_GET['orderby']) && ($_GET['orderby'] == 'dishname' || $_GET['orderby'] == 'price')){
        $orderBy = $_GET['orderby'];
    }
    $direction = "ASC";
    if(isset($_GET['direction']) && ($_GET['direction'] == 'ASC' || $_GET['direction'] == 'DESC')){
        $direction = $_GET['direction'];
    }
    $query = "SELECT * FROM menuitem ORDER BY $orderBy $direction";
    $result = $conn->query($query);
    if($result->num_rows > 0){
        echo "<table>";
        echo "<tr><th>Menu Item ID</th><th>Dish Name</th><th>Price</th><th>Calorie Count</th><th>Veggie</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row['menuitemid']."</td>";
            echo "<td>".$row['dishname']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['caloriecount']."</td>";
            echo "<td>".$row['veggie']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No menu items found.";
    }
    ?>
</div>
</body>
</html>
