<?php
include 'db.php';

// Fetch requisition data (e.g., latest one)
$requisition = $conn->query("SELECT * FROM requisitions ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
$requisition_id = $requisition ? $requisition['id'] : 0;

// Get ALL items and LEFT JOIN with requisition_items to include items without data
$items = $conn->query("
    SELECT i.stock_no, i.unit, i.description, 
           COALESCE(ri.quantity, '') as quantity,
           COALESCE(ri.stock_available_yes, 0) as stock_available_yes, 
           COALESCE(ri.stock_available_no, 0) as stock_available_no, 
           COALESCE(ri.issue_quantity, '') as issue_quantity, 
           COALESCE(ri.remarks, '') as remarks
    FROM items i
    LEFT JOIN requisition_items ri ON i.id = ri.item_id AND ri.requisition_id = $requisition_id
    ORDER BY i.stock_no
");

// Force download as Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=requisition_export.xls");

echo "<table border='1'>";
echo "<tr><th colspan='8' style='font-size:16pt;'>Requisition and Issue Slip</th></tr>";

// Add requisition header information if available
if ($requisition) {
    echo "<tr><td colspan='8'>&nbsp;</td></tr>";
    echo "<tr><td><strong>Entity Name:</strong></td><td colspan='3'>" . htmlspecialchars($requisition['entity_name']) . "</td>";
    echo "<td><strong>Fund Cluster:</strong></td><td colspan='3'>" . htmlspecialchars($requisition['fund_cluster']) . "</td></tr>";
    echo "<tr><td><strong>Division:</strong></td><td colspan='3'>" . htmlspecialchars($requisition['division']) . "</td>";
    echo "<td><strong>RIS No.:</strong></td><td colspan='3'>" . htmlspecialchars($requisition['ris_no']) . "</td></tr>";
    echo "<tr><td><strong>Office:</strong></td><td colspan='3'>" . htmlspecialchars($requisition['office']) . "</td>";
    echo "<td><strong>Responsibility Center:</strong></td><td colspan='3'>" . htmlspecialchars($requisition['responsibility_center']) . "</td></tr>";
    echo "<tr><td colspan='8'>&nbsp;</td></tr>";
}

echo "<tr>
        <th>Stock No.</th>
        <th>Unit</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Stock Available - Yes</th>
        <th>Stock Available - No</th>
        <th>Issue Quantity</th>
        <th>Remarks</th>
      </tr>";

while ($row = $items->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['stock_no']) . "</td>";
    echo "<td>" . htmlspecialchars($row['unit']) . "</td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
    echo "<td>" . ($row['stock_available_yes'] ? 'X' : '') . "</td>";
    echo "<td>" . ($row['stock_available_no'] ? 'X' : '') . "</td>";
    echo "<td>" . htmlspecialchars($row['issue_quantity']) . "</td>";
    echo "<td>" . htmlspecialchars($row['remarks']) . "</td>";
    echo "</tr>";
}

// Add purpose if available
if ($requisition && !empty($requisition['purpose'])) {
    echo "<tr><td colspan='8'>&nbsp;</td></tr>";
    echo "<tr><td><strong>Purpose:</strong></td><td colspan='7'>" . htmlspecialchars($requisition['purpose']) . "</td></tr>";
}

// Add signature section with the new "Received by" column
echo "<tr><td colspan='8'>&nbsp;</td></tr>";
echo "<tr><td colspan='2'>&nbsp;</td><td colspan='2'><strong>Requested by:</strong></td><td><strong>Approved by:</strong></td><td><strong>Issued by:</strong></td><td colspan='2'><strong>Received by:</strong></td></tr>";
echo "<tr><td colspan='2'><strong>Signature:</strong></td><td colspan='2'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td colspan='2'>&nbsp;</td></tr>";

if ($requisition) {
    echo "<tr><td colspan='2'><strong>Printed Name:</strong></td>";
    echo "<td colspan='2'>" . htmlspecialchars($requisition['requesting_officer_name']) . "</td>";
    echo "<td>" . htmlspecialchars($requisition['approved_by_name']) . "</td>";
    echo "<td>" . htmlspecialchars($requisition['issued_by_name']) . "</td>";
    echo "<td colspan='2'>" . htmlspecialchars($requisition['received_by_name'] ?? '') . "</td></tr>";
    
    echo "<tr><td colspan='2'><strong>Designation:</strong></td>";
    echo "<td colspan='2'>" . htmlspecialchars($requisition['requesting_officer_designation']) . "</td>";
    echo "<td>" . htmlspecialchars($requisition['approved_by_designation']) . "</td>";
    echo "<td>" . htmlspecialchars($requisition['issued_by_designation']) . "</td>";
    echo "<td colspan='2'>" . htmlspecialchars($requisition['received_by_designation'] ?? '') . "</td></tr>";
    
    echo "<tr><td colspan='2'><strong>Date:</strong></td>";
    echo "<td colspan='2'>" . htmlspecialchars($requisition['requesting_officer_date']) . "</td>";
    echo "<td>" . htmlspecialchars($requisition['approved_by_date']) . "</td>";
    echo "<td>" . htmlspecialchars($requisition['issued_by_date']) . "</td>";
    echo "<td colspan='2'>" . htmlspecialchars($requisition['received_by_date'] ?? '') . "</td></tr>";
}

echo "</table>";
?>