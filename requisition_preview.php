<?php
include 'db.php';

// Add cache prevention headers at the very beginning
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$success_message = '';
$error_message = '';

// Handle requisition deletion
if (isset($_GET['delete_req']) && is_numeric($_GET['delete_req'])) {
    $delete_id = $_GET['delete_req'];

    // Delete requisition items first (foreign key constraint)
    $conn->query("DELETE FROM requisition_items WHERE requisition_id = $delete_id");

    // Delete requisition header
    $stmt = $conn->prepare("DELETE FROM requisitions WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $success_message = "Requisition deleted successfully!";
    } else {
        $error_message = "Error deleting requisition.";
    }

    // 🔁 Redirect without load_req to clear the slip
    header("Location: requisition_preview.php?success=deleted");
    exit;
}

// Handle loading existing requisition
$selected_requisition = null;
$latest_req = [];

if (isset($_GET['load_req']) && is_numeric($_GET['load_req'])) {
    $req_id = $_GET['load_req'];
    $req_query = $conn->prepare("SELECT * FROM requisitions WHERE id = ?");
    $req_query->bind_param("i", $req_id);
    $req_query->execute();
    $selected_requisition = $req_query->get_result()->fetch_assoc();
    
    if ($selected_requisition) {
        $latest_req = $selected_requisition;
    }
} elseif (!isset($_GET['new'])) {
    // Only load the latest requisition if not explicitly requesting a new slip
    $latest_query = $conn->query("SELECT * FROM requisitions ORDER BY created_at DESC LIMIT 1");
    if ($latest_query && $latest_query->num_rows > 0) {
        $latest_req = $latest_query->fetch_assoc();
    }
} else {
    // Clear slip - do not load any saved requisition
    $latest_req = []; // Force all fields to be blank
}

// Handle form submission for saving requisition data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_requisition'])) {
    $entity_name = $_POST['entity_name'];
    $fund_cluster = $_POST['fund_cluster'];
    $division = $_POST['division'];
    $responsibility_center = $_POST['responsibility_center'];
    $office = $_POST['office'];
    $ris_no = $_POST['ris_no'];
    $purpose = $_POST['purpose'];
    
    // Check if RIS number already exists (for updates)
    $existing_req = $conn->prepare("SELECT id FROM requisitions WHERE ris_no = ?");
    $existing_req->bind_param("s", $ris_no);
    $existing_req->execute();
    $existing_result = $existing_req->get_result();
    $requesting_officer_name = $_POST['requesting_officer_name'] ?? '';
    $requesting_officer_designation = $_POST['requesting_officer_designation'] ?? '';
    $requesting_officer_date = $_POST['requesting_officer_date'] ?? '';
    $approved_by_name = $_POST['approved_by_name'] ?? '';
    $approved_by_designation = $_POST['approved_by_designation'] ?? '';
    $approved_by_date = $_POST['approved_by_date'] ?? '';
    $issued_by_name = $_POST['issued_by_name'] ?? '';
    $issued_by_designation = $_POST['issued_by_designation'] ?? '';
    $issued_by_date = $_POST['issued_by_date'] ?? '';
    $received_by_name = $_POST['received_by_name'] ?? '';
    $received_by_designation = $_POST['received_by_designation'] ?? '';
    $received_by_date = $_POST['received_by_date'] ?? '';
    
    if ($existing_result->num_rows > 0) {
    // Update existing requisition
    $requisition_id = $existing_result->fetch_assoc()['id'];
    $stmt = $conn->prepare("UPDATE requisitions SET entity_name=?, fund_cluster=?, division=?, responsibility_center=?, office=?, purpose=?, requesting_officer=?, authorized_official=?, requesting_officer_name=?, requesting_officer_designation=?, requesting_officer_date=?, approved_by_name=?, approved_by_designation=?, approved_by_date=?, issued_by_name=?, issued_by_designation=?, issued_by_date=?, received_by_name=?, received_by_designation=?, received_by_date=?, updated_at=NOW() WHERE id=?");
    
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    $stmt->bind_param("ssssssssssssssssssssi", $entity_name, $fund_cluster, $division, $responsibility_center, $office, $purpose, $requesting_officer, $authorized_official, $requesting_officer_name, $requesting_officer_designation, $requesting_officer_date, $approved_by_name, $approved_by_designation, $approved_by_date, $issued_by_name, $issued_by_designation, $issued_by_date, $received_by_name, $received_by_designation, $received_by_date, $requisition_id);
} else {
    // Insert new requisition
    $stmt = $conn->prepare("INSERT INTO requisitions (entity_name, fund_cluster, division, responsibility_center, office, ris_no, purpose, requesting_officer, authorized_official, requesting_officer_name, requesting_officer_designation, requesting_officer_date, approved_by_name, approved_by_designation, approved_by_date, issued_by_name, issued_by_designation, issued_by_date, received_by_name, received_by_designation, received_by_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    $stmt->bind_param("sssssssssssssssssssss", $entity_name, $fund_cluster, $division, $responsibility_center, $office, $ris_no, $purpose, $requesting_officer, $authorized_official, $requesting_officer_name, $requesting_officer_designation, $requesting_officer_date, $approved_by_name, $approved_by_designation, $approved_by_date, $issued_by_name, $issued_by_designation, $issued_by_date, $received_by_name, $received_by_designation, $received_by_date);
}
    
    $stmt->execute();
    $requisition_id = $requisition_id ?? $stmt->insert_id;
    
    // Save requisition items
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        // Clear existing items for this requisition
        $conn->query("DELETE FROM requisition_items WHERE requisition_id = $requisition_id");
        
        foreach ($_POST['quantities'] as $item_id => $quantity) {
            $stock_available = $_POST['stock_available'][$item_id] ?? '';
            $stock_available_yes = ($stock_available === 'yes') ? 1 : 0;
            $stock_available_no = ($stock_available === 'no') ? 1 : 0;
            $issue_quantity = $_POST['issue_quantities'][$item_id] ?? '';
            $remarks = $_POST['remarks'][$item_id] ?? '';

            // Only save if at least one field is filled (quantity, stock availability, issue, or remarks)
            if (!empty($quantity) || !empty($stock_available) || !empty($issue_quantity) || !empty($remarks)) {
                $stmt = $conn->prepare("INSERT INTO requisition_items (requisition_id, item_id, quantity, stock_available_yes, stock_available_no, issue_quantity, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiiss", $requisition_id, $item_id, $quantity, $stock_available_yes, $stock_available_no, $issue_quantity, $remarks);
                $stmt->execute();
            }
        }
    }
    
    $success_message = "Requisition saved successfully!";
    // Add timestamp to prevent caching
    header("Location: requisition_preview.php?success=1&new=1&t=" . time());
    exit;
}

// Get all saved requisitions for sidebar
$saved_requisitions = $conn->query("SELECT id, ris_no, entity_name, created_at FROM requisitions ORDER BY created_at DESC");

// Get all items from database
$result = $conn->query(query: "SELECT id, stock_no, description, unit FROM items ORDER BY stock_no");

// Get saved requisition items if loading existing requisition
$saved_items = [];
if ($selected_requisition) {
    $items_query = $conn->prepare("SELECT ri.*, i.stock_no, i.description, i.unit FROM requisition_items ri JOIN items i ON ri.item_id = i.id WHERE ri.requisition_id = ?");
    $items_query->bind_param("i", $selected_requisition['id']);
    $items_query->execute();
    $items_result = $items_query->get_result();
    
    while ($row = $items_result->fetch_assoc()) {
        $saved_items[$row['item_id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/requisition_style.css?v=<?= time() ?>">
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Requisition and Issue Slip - Editable</title>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>📋 Saved RIS
            <button class="btn btn-RSMI">Export RIS to RSMI</button>
        </h3>
        <?php if ($saved_requisitions && $saved_requisitions->num_rows > 0): ?>
            <?php while ($req = $saved_requisitions->fetch_assoc()): ?>
                <div class="requisition-item <?= ($selected_requisition && $selected_requisition['id'] == $req['id']) ? 'active' : '' ?>">
                    <div class="ris-no">RIS: <?= htmlspecialchars($req['ris_no']) ?></div>
                    <div class="entity-name"><?= htmlspecialchars(substr($req['entity_name'], 0, 30)) ?><?= strlen($req['entity_name']) > 30 ? '...' : '' ?></div>
                    <div class="date"><?= date('M j, Y', strtotime($req['created_at'])) ?></div>
                    <div class="requisition-actions">
                        <a href="?load_req=<?= $req['id'] ?>" class="btn-load">Load</a>
                        <a href="?delete_req=<?= $req['id'] ?>" class="btn-delete-req" 
                           onclick="return confirm('Are you sure you want to delete this requisition? This action cannot be undone.')">Delete</a>
                        
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-requisitions">
                No saved RIS found.
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <form method="POST" id="requisitionForm">
                <div class="action-buttons">
                    <a href="office_supplies.php" class="btn btn-back">← Back to Inventory</a>
                    <button type="button" class="btn btn-new" onclick="clearSlip()">🧹 Clear Slip</button>
                    <button type="submit" name="save_requisition" class="btn btn-save">💾 Save</button>
                    <a href="export_excel_RIS.php" class="btn btn-export">📊 Export to Excel</a>
                </div>
                
                <?php if ($success_message): ?>
                    <div class="success-message"><?= $success_message ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="error-message"><?= $error_message ?></div>
                <?php endif; ?>
                
                <div class="center-text">Requisition and Issue Slip</div>
                
                <div class="header-section">
                    <table class="header-table">
                        <tr>
                            <td class="left-align" style="width: 50%;">
                                Entity Name: 
                                <input type="text" name="entity_name" class="editable-input" 
                                       value="<?= htmlspecialchars($latest_req['entity_name'] ?? '') ?>" 
                                       placeholder="Enter entity name">
                            </td>
                            <td class="left-align" style="width: 50%;">
                                Fund Cluster: 
                                <input type="text" name="fund_cluster" class="editable-input" 
                                       value="<?= htmlspecialchars($latest_req['fund_cluster'] ?? '') ?>" 
                                       placeholder="Enter fund cluster">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-align">
                                Division: 
                                <input type="text" name="division" class="editable-input" 
                                       value="<?= htmlspecialchars($latest_req['division'] ?? '') ?>" 
                                       placeholder="Enter division">
                            </td>
                            <td class="left-align">
                                Responsibility Center Code: 
                                <input type="text" name="responsibility_center" class="editable-input" 
                                       value="<?= htmlspecialchars($latest_req['responsibility_center'] ?? '') ?>" 
                                       placeholder="Enter code">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-align">
                                Office: 
                                <input type="text" name="office" class="editable-input" 
                                       value="<?= htmlspecialchars($latest_req['office'] ?? '') ?>" 
                                       placeholder="Enter office">
                            </td>
                            <td class="left-align">
                                RIS No.: 
                                <input type="text" name="ris_no" class="editable-input" 
                                       value="<?= htmlspecialchars($latest_req['ris_no'] ?? '') ?>" 
                                       placeholder="Enter RIS number" required>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <table class="main-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 80px;">Stock No.</th>
                            <th rowspan="2" style="width: 80px;">Unit</th>
                            <th rowspan="2" style="width: 200px;">Description</th>
                            <th rowspan="2" style="width: 80px;">Quantity</th>
                            <th colspan="2" style="width: 120px;">Stock Available?</th>
                            <th colspan="2" style="width: 160px;">Issue</th>
                        </tr>
                        <tr>
                            <th style="width: 60px;">Yes</th>
                            <th style="width: 60px;">No</th>
                            <th style="width: 80px;">Quantity</th>
                            <th style="width: 80px;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Reset result pointer
                        $result->data_seek(0);
                        
                        // Output existing items from database
                        while ($row = $result->fetch_assoc()):
                            $saved_item = $saved_items[$row['id']] ?? null;
                        ?>
                        <tr>
                            <td class="left-align"><?= htmlspecialchars($row['stock_no']) ?></td>
                            <td class="left-align"><?= htmlspecialchars($row['unit']) ?></td>
                            <td class="left-align"><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $row['id'] ?>]" 
                                       class="quantity-input" min="0" placeholder="0"
                                       value="<?= $saved_item ? htmlspecialchars($saved_item['quantity']) : '' ?>">
                            </td>
                                <td class="checkbox-cell">
                                    <input type="radio" name="stock_available[<?= $row['id'] ?>]" value="yes"
                                        <?= ($saved_item && $saved_item['stock_available_yes']) ? 'checked' : '' ?>>
                                </td>
                                <td class="checkbox-cell">
                                    <input type="radio" name="stock_available[<?= $row['id'] ?>]" value="no"
                                        <?= ($saved_item && $saved_item['stock_available_no']) ? 'checked' : '' ?>>
                                </td>
                            <td>
                                <input type="number" name="issue_quantities[<?= $row['id'] ?>]" 
                                       class="quantity-input" min="0" placeholder="0"
                                       value="<?= $saved_item ? htmlspecialchars($saved_item['issue_quantity']) : '' ?>">
                            </td>
                            <td>
                                <input type="text" name="remarks[<?= $row['id'] ?>]" 
                                       class="editable-input" style="width: 70px; min-width: 70px;" 
                                       placeholder="Notes"
                                       value="<?= $saved_item ? htmlspecialchars($saved_item['remarks']) : '' ?>">
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php
                        // Add empty rows to fill the page (adjust number as needed)
                        $empty_rows = max(0, 10 - $result->num_rows);
                        for ($i = 0; $i < $empty_rows; $i++):
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                    <!-- Purpose-->
                    
                        <tr>
                            <td style="text-align: center; font-weight: bold; border-top: 2px solid black;">Purpose</td>
                            <td colspan="7" style="height: 40px; border-top: 2px solid black;">
                                <textarea name="purpose" class="editable-input" style="width: 99%; height: 35px; resize: none; border: none; font-family: inherit;" placeholder="Enter purpose"><?= htmlspecialchars($latest_req['purpose'] ?? '') ?></textarea>
                            </td>
                        </tr>
                    
                        <!-- Signature Block -->
                        <tr>
                        <td colspan="2" style="border: none;"></td> <!-- shift to column 3 -->
                        <th colspan="2">Requested by:</th>
                        <th>Approved by:</th>
                        <th>Issued by:</th>
                        <th colspan="2">Received by:</th>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Signature:</td>
                        <td colspan="2">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Printed Name:</td>
                        <td colspan="2">
                            <input type="text" name="requesting_officer_name" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['requesting_officer_name'] ?? '') ?>" 
                                placeholder="Enter name">
                        </td>
                        <td>
                            <input type="text" name="approved_by_name" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['approved_by_name'] ?? '') ?>" 
                                placeholder="Enter name">
                        </td>
                        <td>
                            <input type="text" name="issued_by_name" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['issued_by_name'] ?? '') ?>" 
                                placeholder="Enter name">
                        </td>
                        <td colspan="2">
                            <input type="text" name="received_by_name" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['received_by_name'] ?? '') ?>" 
                                placeholder="Enter name">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Designation:</td>
                        <td colspan="2">
                            <input type="text" name="requesting_officer_designation" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['requesting_officer_designation'] ?? '') ?>" 
                                placeholder="Enter designation">
                        </td>
                        <td>
                            <input type="text" name="approved_by_designation" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['approved_by_designation'] ?? '') ?>" 
                                placeholder="Enter designation">
                        </td>
                        <td>
                            <input type="text" name="issued_by_designation" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['issued_by_designation'] ?? '') ?>" 
                                placeholder="Enter designation">
                        </td>
                        <td colspan="2">
                            <input type="text" name="received_by_designation" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['received_by_designation'] ?? '') ?>" 
                                placeholder="Enter designation">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Date:</td>
                        <td colspan="2">
                            <input type="date" name="requesting_officer_date" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['requesting_officer_date'] ?? '') ?>">
                        </td>
                        <td>
                            <input type="date" name="approved_by_date" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['approved_by_date'] ?? '') ?>">
                        </td>
                        <td>
                            <input type="date" name="issued_by_date" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['issued_by_date'] ?? '') ?>">
                        </td>
                        <td colspan="2">
                            <input type="date" name="received_by_date" class="editable-input" style="width: 97%;" 
                                value="<?= htmlspecialchars($latest_req['received_by_date'] ?? '') ?>">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <script src="js/requisition_script.js"></script>
    
</body>
</html>