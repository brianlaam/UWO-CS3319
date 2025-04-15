<?php
// modify_menuitem.php
include('config.php');
$error = "";
$message = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Process update
    $menuitemid = $_POST['menuitemid'];
    $price = $_POST['price'];
    $caloriecount = $_POST['caloriecount'];
    $stmt = $conn->prepare("UPDATE menuitem SET price = ?, caloriecount = ? WHERE menuitemid = ?");
    // Bind parameters: price (double), caloriecount (integer), menuitemid (string)
    $stmt->bind_param("dis", $price, $caloriecount, $menuitemid);
    if($stmt->execute()){
        $message = "Menu item updated successfully.";
    } else {
        $error = "Error updating menu item: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modify Menu Item</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Modify Menu Item</h1>
    <?php
    if($error != ""){
        echo "<p class='error'>$error</p>";
    }
    if($message != ""){
        echo "<p class='success'>$message</p>";
    }
    ?>
    <?php
    if(isset($_GET['menuitemid'])){
        // Display the update form for the selected menu item
        $menuitemid = $_GET['menuitemid'];
        $stmt = $conn->prepare("SELECT menuitemid, dishname, price, caloriecount FROM menuitem WHERE menuitemid = ?");
        $stmt->bind_param("s", $menuitemid);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
        if($item){
    ?>
        <form method="post" action="modify_menuitem.php">
            <input type="hidden" name="menuitemid" value="<?php echo $item['menuitemid']; ?>">
            <p><strong>Dish Name:</strong> <?php echo $item['dishname']; ?></p>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required><br><br>
            <label>Calorie Count:</label>
            <input type="number" name="caloriecount" value="<?php echo $item['caloriecount']; ?>" required><br><br>
            <input type="submit" value="Update Menu Item">
        </form>
    <?php
        } else {
            echo "<p class='error'>Menu item not found.</p>";
        }
    } else {
        // Display dropdown to select a menu item to modify
    ?>
        <form method="get" action="modify_menuitem.php">
            <label>Select Menu Item to Modify:</label>
            <select name="menuitemid" required>
                <option value="">--Select Menu Item--</option>
                <?php
                $query = "SELECT menuitemid, dishname FROM menuitem";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()){
                    echo "<option value='".$row['menuitemid']."'>".$row['menuitemid']." - ".$row['dishname']."</option>";
                }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Select">
        </form>
    <?php
    }
    ?>
</div>
</body>
</html>
