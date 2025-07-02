<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add item
    if (!empty($_POST['stock_no']) && !empty($_POST['description']) && !empty($_POST['unit']) && 
        isset($_POST['unit_cost']) && isset($_POST['quantity']) && empty($_POST['action'])) {
        
        $stmt = $conn->prepare("INSERT INTO items (stock_no, description, unit, unit_cost, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdi", $_POST['stock_no'], $_POST['description'], $_POST['unit'], $_POST['unit_cost'], $_POST['quantity']);
        $stmt->execute();
        $id = $stmt->insert_id;

        echo "<tr data-id='$id'>
                <td class='stock_no'>" . htmlspecialchars($_POST['stock_no']) . "</td>
                <td class='description'>" . htmlspecialchars($_POST['description']) . "</td>
                <td class='unit'>" . htmlspecialchars($_POST['unit']) . "</td>
                <td class='unit_cost'>" . htmlspecialchars($_POST['unit_cost']) . "</td>
                <td class='quantity'>" . htmlspecialchars($_POST['quantity']) . "</td>
                <td>
                    <button class='btn edit'>Edit</button>
                    <button class='btn delete'>Delete</button>
                </td>
              </tr>";
        exit;
    }

    // Update item
    if ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $stock_no = $_POST['stock_no'];
        $description = $_POST['description'];
        $unit = $_POST['unit'];
        $unit_cost = $_POST['unit_cost'];
        $quantity = $_POST['quantity'];

        $stmt = $conn->prepare("UPDATE items SET stock_no=?, description=?, unit=?, unit_cost=?, quantity=? WHERE id=?");
        $stmt->bind_param("sssdii", $stock_no, $description, $unit, $unit_cost, $quantity, $id);
        $stmt->execute();
        exit;
    }

    // Delete item
    if ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM items WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        exit;
    }
}
?>