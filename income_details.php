<?php
session_start();
include('con.php'); // Assuming this file contains your database connection details

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];

    // Initialize MySQLi connection with SSL
    include('con.php');

    // Prepare SQL query
    $query = "SELECT * FROM income WHERE added_by = ?";

    // Prepare statement
    if ($stmt = $con->prepare($query)) {

        // Bind parameters
        $stmt->bind_param("i", $id);

        // Execute statement
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>details: " . htmlspecialchars($row['details']) . "</p>";
                echo "<p>amount: Rs." . htmlspecialchars($row['amount']) . "</p>";
                echo "<p>income_date: " . htmlspecialchars($row['income_date']) . "</p><hr/>";
            }
        } else {
            echo "<p>No income details found for this user.</p>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<p>Failed to prepare SQL statement.</p>";
    }

    // Close MySQLi connection
    $con->close();
} else {
    echo "<p>Invalid request.</p>";
}
?>
