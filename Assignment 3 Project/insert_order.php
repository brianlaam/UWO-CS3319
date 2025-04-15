<?php
// insert_order.php
include('config.php');
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect data from the form
    $orderid = $_POST['orderid'];
    $deladdress = $_POST['deladdress'];
    $dateplaced = $_POST['dateplaced'];
    $timeplaced = $_POST['timeplaced'];
    $timedelivered = $_POST['timedelivered'];
    $pickuporder = $_POST['pickuporder'];
    $deliveryrating = $_POST['deliveryrating'];
    $driverid = $_POST['driverid'];
    $cusid = $_POST['cusid'];
    
    // Check if orderid already exists
    $stmt = $conn->prepare("SELECT orderid FROM cusorder WHERE orderid = ?");
    $stmt->bind_param("s", $orderid);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
        $error = "Error: Order ID already exists. Please use a unique Order ID.";
    }
    $stmt->close();
    
    // Check that time placed is before time delivered
    if(strtotime($timeplaced) >= strtotime($timedelivered)){
        $error = "Error: Time placed must be before time delivered.";
    }
    
    // Process menu item quantities (expecting fields like qty_M007, qty_MAAA, etc.)
    $menu_items = array(); // menuitemid => quantity
    $query_menu = "SELECT menuitemid FROM menuitem";
    $result_menu = $conn->query($query_menu);
    while($row = $result_menu->fetch_assoc()){
        $menuitemid = $row['menuitemid'];
        $qty_field = 'qty_' . $menuitemid;
        if(isset($_POST[$qty_field]) && intval($_POST[$qty_field]) > 0){
            $menu_items[$menuitemid] = intval($_POST[$qty_field]);
        }
    }
    
    if(empty($menu_items)){
        $error = "Error: You must order at least one menu item with quantity greater than zero.";
    }
    
    // If no errors, proceed with insertion
    if($error == ""){
        // Insert into cusorder table
        $stmt = $conn->prepare("INSERT INTO cusorder (orderid, deladdress, dateplaced, timeplaced, timedelivered, pickuporder, deliveryrating, driverid, cusid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $orderid, $deladdress, $dateplaced, $timeplaced, $timedelivered, $pickuporder, $deliveryrating, $driverid, $cusid);
        if(!$stmt->execute()){
            $error = "Error inserting order: " . $stmt->error;
        }
        $stmt->close();
        
        // Insert each menu item into overallorder table
        foreach($menu_items as $menuitemid => $quantity){
            $stmt = $conn->prepare("INSERT INTO overallorder (orderid, menuitemid, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $orderid, $menuitemid, $quantity);
            if(!$stmt->execute()){
                $error = "Error inserting order items: " . $stmt->error;
                break;
            }
            $stmt->close();
        }
        
        // Redirect to confirmation page if successful
        if($error == ""){
            header("Location: order_confirmation.php?orderid=" . $orderid);
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insert New Order</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Insert New Order</h1>
    <?php if($error != "") { echo "<p class='error'>$error</p>"; } ?>
    <form method="post" action="insert_order.php">
        <label>Order ID:</label>
        <input type="text" name="orderid" required><br><br>
        
        <label>Delivery Address:</label>
        <input type="text" name="deladdress" required><br><br>
        
        <label>Date Placed:</label>
        <input type="date" name="dateplaced" required><br><br>
        
        <label>Time Placed:</label>
        <input type="time" name="timeplaced" required><br><br>
        
        <label>Time Delivered:</label>
        <input type="time" name="timedelivered" required><br><br>
        
        <label>Pickup Order:</label>
        <input type="radio" name="pickuporder" value="Y" required> Yes
        <input type="radio" name="pickuporder" value="N" required> No<br><br>
        
        <label>Delivery Rating (if applicable):</label>
        <input type="number" name="deliveryrating" min="1" max="5"><br><br>
        
        <label>Select Driver:</label>
        <select name="driverid" required>
            <option value="">--Select Driver--</option>
            <?php
            $query = "SELECT driverid, firstname, lastname FROM driver";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                echo "<option value='".$row['driverid']."'>".$row['driverid']." - ".$row['firstname']." ".$row['lastname']."</option>";
            }
            ?>
        </select>
        <br><br>
        
        <label>Select Customer:</label>
        <select name="cusid" required>
            <option value="">--Select Customer--</option>
            <?php
            $query = "SELECT cusid, firstname, lastname FROM customer";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                echo "<option value='".$row['cusid']."'>".$row['cusid']." - ".$row['firstname']." ".$row['lastname']."</option>";
            }
            ?>
        </select>
        <br><br>
        
        <h3>Menu Items</h3>
        <table>
            <tr>
                <th>Menu Item ID</th>
                <th>Dish Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
            <?php
            $query = "SELECT menuitemid, dishname, price FROM menuitem";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row['menuitemid']."</td>";
                echo "<td>".$row['dishname']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td><input type='number' name='qty_".$row['menuitemid']."' min='0' value='0'></td>";
                echo "</tr>";
            }
            ?>
        </table>
        <br>
        <input type="submit" value="Submit Order">
    </form>
</div>
</body>
</html>
