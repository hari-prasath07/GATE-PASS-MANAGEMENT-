<?php
session_start();
include('db_connect.php'); // Your database connection

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");  // User is either not logged in or not an admin
}

// Add new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Secure hash
    $role = $_POST['role'];

    $checkSql = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Email already exists!');</script>";
    } else {
        $sql_insert = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt_insert->execute()) {
            echo "<script>alert('User added successfully'); window.location.href='admin_dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding user');</script>";
        }
    }
}

// Delete user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $sql_delete = "DELETE FROM users WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $userId);

    if ($stmt_delete->execute()) {
        echo "<script>alert('User deleted successfully');</script>";
    } else {
        echo "Error: " . $stmt_delete->error;
    }
}

// Get all users
$sql = "SELECT id, name, email, role FROM users";
$result = $conn->query($sql);

// Handle the "Download Leave and Gate Pass Logs" action
if (isset($_POST['download_logs'])) {
    // Query to fetch all leave request data
    $leaveSql = "SELECT * FROM leave_requests";
    $leaveResult = $conn->query($leaveSql);

    // Query to fetch all gate pass request data
    $gatePassSql = "SELECT * FROM gate_pass_requests";
    $gatePassResult = $conn->query($gatePassSql);

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

    // Add a page break for the gate pass section
    $html .= '<pagebreak/>';

    // Create HTML content for Gate Pass Logs
    $html .= '<h2>Gate Pass Logs</h2>';
    if ($gatePassResult->num_rows > 0) {
        $html .= '<table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">';
        $html .= '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Date</th><th>Time Out</th><th>Time In</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
        
        while ($row = $gatePassResult->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . $row['id'] . '</td>';
            $html .= '<td>' . $row['name'] . '</td>';
            $html .= '<td>' . $row['email'] . '</td>';
            $html .= '<td>' . $row['date'] . '</td>';
            $html .= '<td>' . $row['time_out'] . '</td>';
            $html .= '<td>' . $row['time_in'] . '</td>';
            $html .= '<td>' . $row['reason'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
    } else {
        $html .= '<p>No gate pass logs found.</p>';
    }

    // Write HTML to the PDF
    $mpdf->WriteHTML($html);

    // Output the PDF as a download
    $mpdf->Output('leave_and_gate_pass_logs.pdf', \Mpdf\Output\Destination::DOWNLOAD);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
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
    <h3>Add New User</h3>
    <form action="admin_dashboard.php" method="post" class="row g-3 mb-4">
      <div class="col-md-3">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
      </div>
      <div class="col-md-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="col-md-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="col-md-2">
        <select name="role" class="form-select" required>
          <option value="">Select Role</option>
          <option value="student">Student</option>
          <option value="warden">Warden</option>
        </select>
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-primary">Add User</button>
      </div>
    </form>

    <h3>All Users</h3>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['name'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= ucfirst($row['role']) ?></td>
              <td>
                <form method="post">
                  <input type="hidden" name="userId" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <h3>Download Logs</h3>
    <!-- Button for downloading both Leave and Gate Pass logs -->
    <form action="admin_dashboard.php" method="post">
      <button type="submit" name="download_logs" class="btn btn-success mb-4">Download Leave and Gate Pass Logs</button>
    </form>
  </div>

</body>
</html>