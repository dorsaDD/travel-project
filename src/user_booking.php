<?php
// Database connection
$host = 'mysql';
$user = 'root';
$password = 'root';
$db = 'travel';
$conn = mysqli_connect($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bookings
$sql = "SELECT * FROM bookings ORDER BY arrivals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .bookings-container {
            max-width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #555;
            text-transform: uppercase;
            font-size: 12px;
        }

        td {
            font-size: 14px;
            color: #333;
        }

        .no-bookings {
            text-align: center;
            color: #666;
            font-size: 18px;
            padding: 20px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            font-size: 14px;
            border-radius: 4px;
            text-align: center;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 12px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="bookings-container">
        <h1>My Bookings</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Location</th>
                        <th>Guests</th>
                        <th>Arrival Date</th>
                        <th>Departure Date</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>

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
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-bookings">
                <p>No bookings found.</p>
            </div>
        <?php endif; ?>
        <a href="user_dashboard.php" class="btn">Back to Dashboard</a>
        

    </div>

    <?php
    // Close connection
    $conn->close();
    ?>
</body>
</html>

