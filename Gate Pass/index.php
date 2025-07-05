<?php
// Include the database connection
include('db_connect.php');

// Write the SQL query
$sql = "SELECT id, name, email, role FROM users";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output the data of each row
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
    echo "0 results";
}

// Close the connection
$conn->close();
?>