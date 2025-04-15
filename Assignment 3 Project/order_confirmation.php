<?php
// order_confirmation.php
include('config.php');
if(!isset($_GET['orderid'])){
    die("No order specified.");
}
$orderid = $_GET['orderid'];

// Retrieve order details along with driver and customer names
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

// Retrieve order items and compute total price for each item and the order overall
$query = "SELECT oi.menuitemid, mi.dishname, mi.price, oi.quantity, (mi.price * oi.quantity) AS item_total
          FROM overallorder oi
          LEFT JOIN menuitem mi ON oi.menuitemid = mi.menuitemid
          WHERE oi.orderid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $orderid);
$stmt->execute();
$result = $stmt->get_result();
$order_items = [];
$total_price = 0;
while($row = $result->fetch_assoc()){
    $order_items[] = $row;
    $total_price += $row['item_total'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <a href="mainmenu.php">Back to Main Menu</a>
    <h1>Order Confirmation</h1>
    <h2>Order Details</h2>
    <p><strong>Order ID:</strong> <?php echo $order['orderid']; ?></p>
    <p><strong>Delivery Address:</strong> <?php echo $order['deladdress']; ?></p>
    <p><strong>Date Placed:</strong> <?php echo $order['dateplaced']; ?></p>
    <p><strong>Time Placed:</strong> <?php echo $order['timeplaced']; ?></p>
    <p><strong>Time Delivered:</strong> <?php echo $order['timedelivered']; ?></p>
    <p><strong>Pickup Order:</strong> <?php echo $order['pickuporder']; ?></p>
    <p><strong>Delivery Rating:</strong> <?php echo $order['deliveryrating']; ?></p>
    <p><strong>Driver:</strong> <?php echo $order['driver_first'] . " " . $order['driver_last']; ?></p>
    <p><strong>Customer:</strong> <?php echo $order['customer_first'] . " " . $order['customer_last']; ?></p>
    
    <h2>Ordered Menu Items</h2>
    <table>
        <tr>
            <th>Menu Item ID</th>
            <th>Dish Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Item Total</th>
        </tr>
        <?php foreach($order_items as $item): ?>
        <tr>
            <td><?php echo $item['menuitemid']; ?></td>
            <td><?php echo $item['dishname']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['item_total']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h3>Total Order Price: <?php echo $total_price; ?></h3>
</div>
</body>
</html>
