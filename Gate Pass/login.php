<?php
session_start();
include('db_connect.php'); // Your database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prepare and execute SQL query
    $sql = "SELECT * FROM users WHERE email=? AND password=? AND role=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Store session details
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // 👇 Add or update student info only if role is student
        if ($user['role'] == 'student') {
            $checkSql = "SELECT * FROM students WHERE email = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows == 0) {
                // Insert new student
                $insertSql = "INSERT INTO students (name, email) VALUES (?, ?)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param("ss", $user['name'], $user['email']);
                $insertStmt->execute();
            } else {
                // Update student name
                $updateSql = "UPDATE students SET name = ? WHERE email = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("ss", $user['name'], $user['email']);
                $updateStmt->execute();
            }
        }

        // Redirect based on role
        if ($user['role'] == 'student') {
            header("Location: student_dashboard.php");
            exit;
        } elseif ($user['role'] == 'warden') {
            header("Location: warden_dashboard.php");
            exit;
        } elseif ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Gate Pass System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f0f2f5;
    }
    .login-container {
      max-width: 400px;
      margin: 80px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h3 class="text-center mb-4">Hostel Gate Pass Login</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="login.php" method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Login As</label>
        <select name="role" class="form-select" required>
          <option value="">-- Select Role --</option>
          <option value="student">Student</option>
          <option value="warden">Warden</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

</body>
</html>
