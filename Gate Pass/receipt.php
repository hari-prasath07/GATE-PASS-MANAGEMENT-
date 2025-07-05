<?php
session_start();
include('db_connect.php');
require_once __DIR__ . '/vendor/autoload.php';

echo "Connected successfully<br>";

// Get session details
$email = $_SESSION['email'];
$name = $_SESSION['name'];
echo "Session Email: $email<br>";

// Get request ID
$request_id = $_GET['id'] ?? null;
if (!$request_id) {
    die('Invalid Request.');
}

// Try to fetch Gate Pass first
$sqlGate = "SELECT * FROM gate_pass_requests WHERE id = ? AND status = 'Approved'";
$stmtGate = $conn->prepare($sqlGate);
$stmtGate->bind_param("i", $request_id);
$stmtGate->execute();
$resultGate = $stmtGate->get_result();

if ($resultGate->num_rows > 0) {
    $data = $resultGate->fetch_assoc();
    $type = "Gate Pass";
    $date = $data['date'];
    $time_out = $data['time_out'];
    $time_in = $data['time_in'];
    $reason = $data['reason'];
} else {
    // Try to fetch Leave request
    $sqlLeave = "SELECT * FROM leave_requests WHERE id = ? AND status = 'Approved'";
    $stmtLeave = $conn->prepare($sqlLeave);
    $stmtLeave->bind_param("i", $request_id);
    $stmtLeave->execute();
    $resultLeave = $stmtLeave->get_result();

    if ($resultLeave->num_rows > 0) {
        $data = $resultLeave->fetch_assoc();
        $type = "Leave";
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $reason = $data['reason'];
    } else {
        die("Request not found or not approved.");
    }
}

// Start creating PDF
$mpdf = new \Mpdf\Mpdf();
$html = "<h2>Hostel Request Receipt</h2>";
$html .= "<p><strong>Name:</strong> $name</p>";
$html .= "<p><strong>Email:</strong> $email</p>";
$html .= "<p><strong>Type:</strong> $type</p>";

if ($type === "Leave") {
    $html .= "<p><strong>From Date:</strong> $from_date</p>";
    $html .= "<p><strong>To Date:</strong> $to_date</p>";
} else {
    $html .= "<p><strong>Date:</strong> $date</p>";
    $html .= "<p><strong>Time Out:</strong> $time_out</p>";
    $html .= "<p><strong>Expected Time In:</strong> $time_in</p>";
}
$html .= "<p><strong>Reason:</strong> $reason</p>";
$html .= "<p><strong>Status:</strong> Approved</p>";

// Output PDF
$mpdf->WriteHTML($html);
$mpdf->Output('receipt.pdf', \Mpdf\Output\Destination::DOWNLOAD);
exit();
?>
