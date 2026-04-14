<?php
// Simple Pharmacy Management System API (Assignment Style)

$connect = mysqli_connect("localhost", "YOUR_USERNAME", "YOUR_PASSWORD", "YOUR_DB_NAME");

if (!$connect) {
    die("<h2 style='color: red;'>Connection failed: " . mysqli_connect_error() . "</h2>");
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// 1. Drop Tables
if ($action == "drop") {
    mysqli_query($connect, "DROP TABLE IF EXISTS medicines");
    echo "<h3 style='color: green;'>Tables dropped successfully!</h3>";
    exit;
}

// 2. Create Tables
if ($action == "create") {
    $sql = "CREATE TABLE medicines (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        price DECIMAL(10,2),
        quantity INT
    )";
    if (mysqli_query($connect, $sql)) {
        echo "<h3 style='color: green;'>Table 'medicines' created successfully!</h3>";
    } else {
        echo "<h3 style='color: red;'>Error creating tables: " . mysqli_error($connect) . "</h3>";
    }
    exit;
}

// 3. Populate Tables
if ($action == "populate") {
    $sql = "
    INSERT IGNORE INTO medicines (name, price, quantity) VALUES
    ('Paracetamol', 5.99, 150),
    ('Ibuprofen', 8.50, 20),
    ('Amoxicillin', 12.00, 5),
    ('Cough Syrup', 7.25, 45),
    ('Vitamin C', 15.00, 80)
    ";
    if (mysqli_query($connect, $sql)) {
        echo "<h3 style='color: green;'>Dummy data inserted successfully!</h3>";
    } else {
        echo "<h3 style='color: red;'>No new records inserted (error: " . mysqli_error($connect) . ")</h3>";
    }
    exit;
}

// 4. Raw Query Execution
if ($action == "query") {
    $q = isset($_POST['query']) ? $_POST['query'] : '';
    if (empty($q)) {
        die("<h3 style='color: red;'>Query is empty!</h3>");
    }
    
    $result = mysqli_query($connect, $q);
    
    if (!$result) {
        die("<h3 style='color: red;'>Error executing query: " . mysqli_error($connect) . "</h3>");
    }

    if ($result === true) {
        echo "<h3 style='color: green;'>Query executed successfully!</h3>";
        exit;
    }

    // Print out HTML table if it's a SELECT query
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; margin-top: 20px; width: 100%;'>";
    echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>$" . number_format($row['price'], 2) . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    exit;
}

echo "<h3>Invalid action specified.</h3>";

mysqli_close($connect);
?>
