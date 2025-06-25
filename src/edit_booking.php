<?php
// Database connection
$host = 'mysql';
$user ='root';
$password = 'root';
$db = 'travel';
$conn = mysqli_connect($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the booking ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the booking details
    $sql = "SELECT * FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        die("Booking not found!");
    }

    // Handle form submission for updating the booking
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $address = htmlspecialchars(trim($_POST['address']));
        $location = htmlspecialchars(trim($_POST['location']));
        $guests = intval($_POST['guests']);
        $arrivals = $_POST['arrivals'];
        $leaving = $_POST['leaving'];

        $update_sql = "UPDATE bookings SET name = ?, email = ?, phone = ?, address = ?, location = ?, guests = ?, arrivals = ?, leaving = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssssss", $name, $email, $phone, $address, $location, $guests, $arrivals, $leaving, $id);

        if ($update_stmt->execute()) {
            // Redirect without output
            header("Location: admin_dashboard.php");
            exit; // Terminate the script after redirect
        } else {
            echo "Error updating booking: " . $conn->error;
        }
    }
} else {
    die("Invalid request!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>



</head>
<body>
    <h1>Edit Booking</h1>
    <form method="POST">
        <label>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($booking['name']); ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required></label><br>
        <label>Phone: <input type="text" name="phone" value="<?php echo htmlspecialchars($booking['phone']); ?>" required></label><br>
        <label>Address: <input type="text" name="address" value="<?php echo htmlspecialchars($booking['address']); ?>" required></label><br>
        <label>Location: <input type="character" name="location" value="<?php echo htmlspecialchars($booking['location']); ?>" required></label><br>
        <label>Guests: <input type="number" name="guests" value="<?php echo htmlspecialchars($booking['guests']); ?>" required></label><br>
        <label>Arrival Date: <input type="date" name="arrivals" value="<?php echo htmlspecialchars($booking['arrivals']); ?>" required></label><br>
        <label>Departure Date: <input type="date" name="leaving" value="<?php echo htmlspecialchars($booking['leaving']); ?>" required></label><br>
        <button type="submit">Update Booking</button>
    </form>
</body>
</html>
