<?php
include 'db.php';

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=inventory_export.xls");

// Start of table
echo "<table border='1'>";
echo "<tr><th colspan='3' style='font-size:16pt;'>Stationary Supplies</th></tr>";
echo "<tr><th>Stock No.</th><th>Description</th><th>Unit</th></tr>";

// Fetch and output data
$result = $conn->query("SELECT * FROM items ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['stock_no']) . "</td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "<td>" . htmlspecialchars($row['unit']) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
