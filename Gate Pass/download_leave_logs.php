<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
include('db_connect.php');

// Make sure only admin can access
if ($_SESSION['role'] !== 'admin') {
    die('Access denied.');
}

$sql = "SELECT * FROM leave_requests ORDER BY id DESC";
$result = $conn->query($sql);

$mpdf = new \Mpdf\Mpdf();
$html = "<h2 style='text-align:center;'>Leave Request Logs</h2>";
$html .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
$html .= "<tr style='background:#f2f2f2;'>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>Status</th>
          </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['from_date']}</td>
                    <td>{$row['to_date']}</td>
                    <td>{$row['reason']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
    }
} else {
    $html .= "<tr><td colspan='7'>No leave records found.</td></tr>";
}

$html .= "</table>";

$mpdf->WriteHTML($html);
$mpdf->Output('Leave_Logs.pdf', \Mpdf\Output\Destination::DOWNLOAD);
exit;
?>
