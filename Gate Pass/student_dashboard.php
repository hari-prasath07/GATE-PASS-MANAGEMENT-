<?php
session_start();
include('db_connect.php');

// Get student email from session
$student_email = $_SESSION['email'] ?? 'student1@gmail.com';

// Handle Leave Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leaveSubmit'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $reason = $_POST['reason'];
    $sql = "INSERT INTO leave_requests (student_email, from_date, to_date, reason, status)
            VALUES ('$student_email', '$fromDate', '$toDate', '$reason', 'Pending')";
    $conn->query($sql);
    header("Location: student_dashboard.php");
    exit();
}

// Handle Gate Pass Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gatePassSubmit'])) {
    $date = $_POST['date'];
    $timeOut = $_POST['timeOut'];
    $timeIn = $_POST['timeIn'];
    $reason = $_POST['reason'];
    $sql = "INSERT INTO gate_pass_requests (student_email, date, time_out, time_in, reason, status)
            VALUES ('$student_email', '$date', '$timeOut', '$timeIn', '$reason', 'Pending')";
    $conn->query($sql);
    header("Location: student_dashboard.php");
    exit();
}

// Fetch Leave & Gate Pass Requests
$allRequests = [];
$leaveQuery = "SELECT id, 'Leave' as type, from_date as start, to_date as end, reason, status FROM leave_requests WHERE student_email='$student_email'";
$gateQuery = "SELECT id, 'Gate Pass' as type, date as start, date as end, reason, status FROM gate_pass_requests WHERE student_email='$student_email'";

$leaveResult = $conn->query($leaveQuery);
$gateResult = $conn->query($gateQuery);

while ($row = $leaveResult->fetch_assoc()) {
    $allRequests[] = $row;
}
while ($row = $gateResult->fetch_assoc()) {
    $allRequests[] = $row;
}

// Fetch only approved gate pass for receipt section
$approvedReceipts = [];
$approvedQuery = "SELECT id, date, time_out, time_in, reason FROM gate_pass_requests WHERE student_email='$student_email' AND status='Approved'";
$approvedResult = $conn->query($approvedQuery);

// Fetch only approved leave requests for receipt section
$approvedLeaveReceipts = [];
$approvedLeaveQuery = "SELECT id, from_date, to_date, reason FROM leave_requests WHERE student_email='$student_email' AND status='Approved'";
$approvedLeaveResult = $conn->query($approvedLeaveQuery);

// Debugging - Check if the query returns results
if ($approvedResult->num_rows > 0) {
    while ($row = $approvedResult->fetch_assoc()) {
        $approvedReceipts[] = $row;
    }
}

if ($approvedLeaveResult->num_rows > 0) {
    while ($row = $approvedLeaveResult->fetch_assoc()) {
        $approvedLeaveReceipts[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Hostel Gate Pass</a>
      <div class="d-flex">
        <a class="nav-link text-white" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">

    <h3>Apply for Leave</h3>
    <form method="post" class="mb-4">
      <div class="mb-3"><label>From Date</label><input type="date" name="fromDate" class="form-control" required></div>
      <div class="mb-3"><label>To Date</label><input type="date" name="toDate" class="form-control" required></div>
      <div class="mb-3"><label>Reason</label><textarea name="reason" class="form-control" required></textarea></div>
      <button type="submit" name="leaveSubmit" class="btn btn-primary">Submit Leave</button>
    </form>

    <h3>Request Gate Pass</h3>
    <form method="post" class="mb-4">
      <div class="mb-3"><label>Date</label><input type="date" name="date" class="form-control" required></div>
      <div class="mb-3"><label>Time Out</label><input type="time" name="timeOut" class="form-control" required></div>
      <div class="mb-3"><label>Expected Time In</label><input type="time" name="timeIn" class="form-control" required></div>
      <div class="mb-3"><label>Reason</label><textarea name="reason" class="form-control" required></textarea></div>
      <button type="submit" name="gatePassSubmit" class="btn btn-success">Submit Gate Pass</button>
    </form>

    <h3>Your Requests</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Type</th>
          <th>Dates</th>
          <th>Reason</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($allRequests as $req): ?>
          <tr>
            <td><?= $req['type'] ?></td>
            <td><?= $req['start'] ?> <?= $req['type'] === 'Leave' ? 'to ' . $req['end'] : '' ?></td>
            <td><?= $req['reason'] ?></td>
            <td><?= $req['status'] ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (count($allRequests) === 0): ?>
          <tr><td colspan="4">No requests submitted.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- RECEIPT DOWNLOAD SECTION -->
    <h3 class="mt-5">Download Approved Leave Receipts</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>From Date</th>
          <th>To Date</th>
          <th>Reason</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($approvedLeaveReceipts) > 0): ?>
          <?php foreach ($approvedLeaveReceipts as $leaveReceipt): ?>
            <tr>
              <td><?= $leaveReceipt['from_date'] ?></td>
              <td><?= $leaveReceipt['to_date'] ?></td>
              <td><?= $leaveReceipt['reason'] ?></td>
              <td>
                <form method="get" action="receipt.php">
                  <input type="hidden" name="id" value="<?= $leaveReceipt['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-warning">Download PDF</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4">No approved leave requests yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- RECEIPT DOWNLOAD SECTION (Gate Pass) -->
    <h3 class="mt-5">Download Approved Gate Pass Receipts</h3>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Date</th>
          <th>Time Out</th>
          <th>Time In</th>
          <th>Reason</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($approvedReceipts) > 0): ?>
          <?php foreach ($approvedReceipts as $receipt): ?>
            <tr>
              <td><?= $receipt['date'] ?></td>
              <td><?= $receipt['time_out'] ?></td>
              <td><?= $receipt['time_in'] ?></td>
              <td><?= $receipt['reason'] ?></td>
              <td>
                <form method="get" action="receipt.php">
                  <input type="hidden" name="id" value="<?= $receipt['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-warning">Download PDF</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">No approved gate pass yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>
</body>
</html>
