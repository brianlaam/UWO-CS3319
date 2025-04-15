<?php
// delete_menuitem.php
include('config.php');
$error = "";
$message = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['menuitemid'])){
        $menuitemid = $_POST['menuitemid'];
        if($_POST['confirm'] == 'yes'){
            // Check if the menu item is used in any orders
            $stmt = $conn->prepare("SELECT * FROM overallorder WHERE menuitemid = ?");
            $stmt->bind_param("s", $menuitemid);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0){
                $error = "This menu item is part of existing orders and cannot be deleted.";
            } else {
                $stmt->close();
                $stmt = $conn->prepare("DELETE FROM menuitem WHERE menuitemid = ?");
                $stmt->bind_param("s", $menuitemid);
                if($stmt->execute()){
                    $message = "Menu item deleted successfully.";
                } else {
                    $error = "Error deleting menu item: " . $stmt->error;
                }
            }
            $stmt->close();
        } else {
            $message = "Deletion cancelled.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Menu Item</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Delete Menu Item</h1>
    <?php
    if($error != ""){
        echo "<p class='error'>$error</p>";
    }
    if($message != ""){
        echo "<p class='success'>$message</p>";
    }
    ?>
    <form method="post" action="delete_menuitem.php">
        <label>Select Menu Item to Delete:</label>
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
        <label>Are you sure you want to delete this menu item?</label>
        <input type="radio" name="confirm" value="yes" required> Yes
        <input type="radio" name="confirm" value="no" required> No
        <br><br>
        <input type="submit" value="Delete Menu Item">
    </form>
</div>
</body>
</html>
