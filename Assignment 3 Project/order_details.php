<?php
// order_details.php
include('config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Order Details</h1>
    <?php
    if(isset($_GET['orderid'])){
        $orderid = $_GET['orderid'];
        // Get order details along with driver and customer information
        $query = "SELECT co.orderid, co.deladdress, co.dateplaced, co.timeplaced, co.timedelivered, co.deliveryrating, co.pickuporder,
                  d.firstname AS driver_first, d.lastname AS driver_last,
                  c.firstname AS customer_first, c.lastname AS customer_last
                  FROM cusorder co
                  LEFT JOIN driver d ON co.driverid = d.driverid
                  LEFT JOIN customer c ON co.cusid = c.cusid
                  WHERE co.orderid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $orderid);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        
        if($order){
            echo "<h2>Order ID: " . $order['orderid'] . "</h2>";
            echo "<p><strong>Delivery Address:</strong> " . $order['deladdress'] . "</p>";
            echo "<p><strong>Date Placed:</strong> " . $order['dateplaced'] . "</p>";
            echo "<p><strong>Time Placed:</strong> " . $order['timeplaced'] . "</p>";
            echo "<p><strong>Time Delivered:</strong> " . $order['timedelivered'] . "</p>";
            echo "<p><strong>Pickup Order:</strong> " . $order['pickuporder'] . "</p>";
            echo "<p><strong>Delivery Rating:</strong> " . $order['deliveryrating'] . "</p>";
            echo "<p><strong>Driver:</strong> " . $order['driver_first'] . " " . $order['driver_last'] . "</p>";
            echo "<p><strong>Customer:</strong> " . $order['customer_first'] . " " . $order['customer_last'] . "</p>";
            
            // Get order items details
            $query = "SELECT oi.menuitemid, mi.dishname, mi.price, oi.quantity, (mi.price * oi.quantity) AS item_total
                      FROM overallorder oi
                      LEFT JOIN menuitem mi ON oi.menuitemid = mi.menuitemid
                      WHERE oi.orderid = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $orderid);
            $stmt->execute();
            $result = $stmt->get_result();
            echo "<h3>Ordered Menu Items</h3>";
            echo "<table>";
            echo "<tr><th>Menu Item ID</th><th>Dish Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
            $total_order = 0;
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row['menuitemid']."</td>";
                echo "<td>".$row['dishname']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td>".$row['quantity']."</td>";
                echo "<td>".$row['item_total']."</td>";
                echo "</tr>";
                $total_order += $row['item_total'];
            }
            echo "</table>";
            echo "<h3>Total Order Price: " . $total_order . "</h3>";
            $stmt->close();
        } else {
            echo "<p class='error'>Order not found.</p>";
        }
    } else {
    ?>
    <form method="get" action="order_details.php">
        <label>Select Order:</label>
        <select name="orderid" required>
            <option value="">--Select Order--</option>
            <?php
            $query = "SELECT orderid FROM cusorder";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                echo "<option value='".$row['orderid']."'>".$row['orderid']."</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="View Order Details">
    </form>
    <?php
    }
    ?>
</div>
</body>
</html>
