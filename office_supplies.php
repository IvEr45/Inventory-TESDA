<?php include 'db.php';?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <title>Inventory</title>

</head>
<body>
    <h2>Stationary Supplies</h2>

    <div class="top-controls">
        <div class="left-controls">
            <button class="add-item-btn" onclick="openAddModal()">+ Add New Item</button>
            <div class="dropdown-container">
                <select class="dropdown-select">
                    <option value="">Filter Options</option>
                    <option value="option1">Category A</option>
                    <option value="option2">Category B</option>

                </select>
            </div>
        </div>
        <div class="right-controls">
            <a href="export_excel_office.php" class="export-btn">Export as Excel</a>
        </div>
    </div>
    <div style="margin-bottom: 10px;">
        <input type="text" id="itemSearch" placeholder="🔍 Search stock no., description, or unit..."
            style="width: 100%; padding: 6px; font-size: 16px;">
    </div>

    <div class="table-container">
        <table id="itemsTable">
            <thead>
                <tr>
                    <th>Stock No.</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM items ORDER BY id DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr data-id="<?= $row['id'] ?>">
                    <td class="stock_no"><?= htmlspecialchars($row['stock_no']) ?></td>
                    <td class="description"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="unit"><?= htmlspecialchars($row['unit']) ?></td>
                    <td class="unit_cost"><?= htmlspecialchars($row['unit_cost'] ?? '0') ?></td>
                    <td class="quantity"><?= htmlspecialchars($row['quantity'] ?? '0') ?></td>
                    <td>
                        <button class="btn edit">Edit</button>
                        <button class="btn delete">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Item Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Item</h3>
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <form id="addForm" class="modal-form">
                <div>
                    <label>Stock Number</label>
                    <input type="text" name="stock_no" placeholder="Enter stock number" required>
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" name="description" placeholder="Enter item description" required>
                </div>
                <div>
                    <label>Unit</label>
                    <input type="text" name="unit" placeholder="Enter unit (e.g., Piece, Box, Ream)" required>
                </div>
                <div>
                    <label>Unit Cost</label>
                    <input type="number" name="unit_cost" placeholder="Enter unit cost" min="0" step="0.01" required>
                </div>
                <div>
                    <label>Quantity</label>
                    <input type="number" name="quantity" placeholder="Enter quantity" min="0" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="modal-btn secondary" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="modal-btn primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Item</h3>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form id="editForm" class="modal-form">
                <input type="hidden" id="editId" name="id">
                <div>
                    <label>Stock Number</label>
                    <input type="text" id="editStockNo" name="stock_no" placeholder="Enter stock number" required>
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" id="editDescription" name="description" placeholder="Enter item description" required>
                </div>
                <div>
                    <label>Unit</label>
                    <input type="text" id="editUnit" name="unit" placeholder="Enter unit (e.g., Piece, Box, Ream)" required>
                </div>
                <div>
                    <label>Unit Cost</label>
                    <input type="number" id="editUnitCost" name="unit_cost" placeholder="Enter unit cost" min="0" step="0.01" required>
                </div>
                <div>
                    <label>Quantity</label>
                    <input type="number" id="editQuantity" name="quantity" placeholder="Enter quantity" min="0" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="modal-btn secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="modal-btn primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script src="js/script.js?v=<?= time() ?>"></script>
</body>
</html>