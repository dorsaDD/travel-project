<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php'); // Redirect to login page if not logged in
    exit;
}

// Database connection
$host = 'mysql';
$user = 'root';
$password = 'root';
$db = 'travel';

$conn = mysqli_connect($host, $user, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$bookings_result = false;
$successMessage = '';
$errorMessage = '';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM bookings WHERE id = ?";
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $successMessage = "Booking deleted successfully!";
        } else {
            $errorMessage = "Error deleting booking: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Error preparing delete statement: " . $conn->error;
    }
}

// Fetch bookings
$bookings_query = "SELECT * FROM bookings ORDER BY arrivals DESC";
$bookings_result = $conn->query($bookings_query);

if (!$bookings_result) {
    $errorMessage = "Failed to fetch bookings: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Bookings</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        background-color: #ecf0f3;
        color: #2d3436;
    }

    .manage-bookings-container {
        max-width: 1150px;
        margin: 40px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #0984e3;
        margin-bottom: 30px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #fafafa;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 14px 18px;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #00b894;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #dff9fb;
    }

    .btn {
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        margin-top: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn.edit {
        background-color: #0984e3;
        color: #fff;
    }

    .btn.edit:hover {
        background-color: #066acb;
    }

    .btn.delete-btn {
        background-color: #d63031;
        color: #fff;
    }

    .btn.delete-btn:hover {
        background-color: #c0392b;
    }

    .btn.back {
        background-color: #2d3436;
        color: #fff;
        padding: 10px 22px;
        font-size: 15px;
        margin: 0 auto;
        display: block;
        border-radius: 8px;
    }

    .btn.back:hover {
        background-color: #636e72;
    }

    .success, .error {
        padding: 12px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        max-width: 500px;
        text-align: center;
        font-weight: 600;
        margin-left: auto;
        margin-right: auto;
    }

    .success {
        background-color: #d1f5d3;
        color: #256029;
        border: 1px solid #b3e6b5;
    }

    .error {
        background-color: #fdecea;
        color: #b10000;
        border: 1px solid #f5c2c7;
    }

    @media (max-width: 768px) {
        .manage-bookings-container {
            padding: 25px;
        }

        table, th, td {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
            padding: 6px 10px;
        }

        .btn.back {
            padding: 8px 16px;
        }
    }
</style>

</head>
<body>
    <div class="manage-bookings-container">
        <h1>Manage Bookings</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <?php if ($bookings_result && $bookings_result instanceof mysqli_result && $bookings_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Location</th>
                        <th>Guests</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $bookings_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['guests']); ?></td>
                            <td><?php echo htmlspecialchars($row['arrivals']); ?></td>
                            <td><?php echo htmlspecialchars($row['leaving']); ?></td>
                            <td>
                                <a href="edit_booking.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                                <a href="manage_bookings.php?delete_id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this booking?');" 
                                   class="btn delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; font-size: 18px; color: #666;">No bookings found.</p>
        <?php endif; ?>

        <a href="admin_dashboard.php" class="btn back">Back to Dashboard</a>
    </div>

<?php
// Close the database connection
$conn->close();
?>
</body>
</html>