<?php
// Include the database connection
include('db_connect.php');

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Insert the new user into the database
    $sql = "INSERT INTO users (name, email, password, role) 
            VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all users to display them
$sql = "SELECT id, name, email, role FROM users";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add and View Users</title>
</head>
<body>

<h2>Add New User</h2>
<!-- User input form -->
<form method="POST" action="add_user.php">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="text" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Role: <input type="text" name="role" required><br><br>
    <input type="submit" value="Add User">
</form>

<h2>List of Users</h2>
<!-- Display users in a table -->
<?php
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["name"]. "</td>
                <td>" . $row["email"]. "</td>
                <td>" . $row["role"]. "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No users found.";
}
?>

</body>
</html>
