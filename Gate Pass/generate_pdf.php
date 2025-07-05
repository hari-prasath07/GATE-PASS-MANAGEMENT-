<?php
session_start();
include('db_connect.php'); // Your database connection

// Ensure user is logged in and has admin privileges
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");  // User is either not logged in or not an admin
}

// Handle the "Download Leave Logs" action
if (isset($_POST['download_leave_logs'])) {
    // Query to fetch all leave request data
    $leaveSql = "SELECT * FROM leave_requests";
    $leaveResult = $conn->query($leaveSql);

    require_once __DIR__ . '/vendor/autoload.php';  // Ensure mPDF is loaded
    $mpdf = new \Mpdf\Mpdf();

    // Create HTML content for Leave Logs
    $html = '<h2>Leave Logs</h2>';
    if ($leaveResult->num_rows > 0) {
        $html .= '<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">';
        $html .= '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>From Date</th><th>To Date</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
        
        while ($row = $leaveResult->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . $row['id'] . '</td>';
            $html .= '<td>' . $row['name'] . '</td>';
            $html .= '<td>' . $row['email'] . '</td>';
            $html .= '<td>' . $row['from_date'] . '</td>';
            $html .= '<td>' . $row['to_date'] . '</td>';
            $html .= '<td>' . $row['reason'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
    } else {
        $html .= '<p>No leave logs found.</p>';
    }

    // Write HTML to the PDF
    $mpdf->WriteHTML($html);

    // Output the PDF as a download
    $mpdf->Output('leave_logs.pdf', \Mpdf\Output\Destination::DOWNLOAD);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Download Leave Logs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Admin Dashboard</a>
      <a class="nav-link text-white" href="logout.php">Logout</a>
    </div>
  </nav>

  <div class="container mt-4">
    <h3>Download Leave Logs</h3>
    <!-- Button for downloading leave logs -->
    <form action="generate_leave_logs_pdf.php" method="post">
      <button type="submit" name="download_leave_logs" class="btn btn-success mb-4">Download Leave Logs</button>
    </form>
  </div>

</body>
</html>
