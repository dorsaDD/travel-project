<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

$host = 'mysql';
$user = 'root';
$password = 'root';
$db = 'travel';
$conn = mysqli_connect($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = '';
$errorMessage = '';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $user_id);

    if ($stmt->execute()) {
        $successMessage = "User deleted successfully!";
    } else {
        $errorMessage = "Error deleting user: " . $stmt->error;
    }
    $stmt->close();
}

$sql = "SELECT id, firstname, middlename, lastname, phone, address, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users</title>
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

        .dashboard-container {
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

        p {
            text-align: center;
            font-size: 1.1rem;
            color: #636e72;
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
            margin-top: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn.edit {
            background-color: #0984e3;
            color: white;
        }

        .btn.delete {
            background-color: #d63031;
            color: white;
            border: none;
        }

        .btn.back {
            background-color: #2d3436;
            color: white;
            padding: 10px 22px;
            font-size: 15px;
            margin: 0 auto;
            display: block;
            border-radius: 8px;
            text-align: center;
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
            .dashboard-container {
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
    <div class="dashboard-container">
        <h1>Manage Users</h1>
        <p>Welcome, Admin!</p>

        <?php if (!empty($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <section>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First</th>
                        <th>Middle</th>
                        <th>Last</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($row['middlename']); ?></td>
                                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                                    <form action="" method="post" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_user" class="btn delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <a href="admin_dashboard.php" class="btn back">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
