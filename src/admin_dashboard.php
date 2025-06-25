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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #e8f0fe;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .dashboard-container {
            background-color: #2d3436;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            color: white;
        }

        h1 {
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 10px;
            color: #ffffff;
        }

        p {
            text-align: center;
            font-size: 1.2rem;
            color: #dfe6e9;
            margin-bottom: 30px;
        }

        section {
            margin: 20px 0;
            text-align: center;
        }

        h2 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #81ecec;
        }

        .btn {
            padding: 12px 22px;
            font-size: 1rem;
            color: white;
            background: #00cec9;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
            display: inline-block;
            margin-top: 8px;
        }

        .btn:hover {
            background: #00b8b2;
        }

        .btn.logout-btn {
            background-color: #d63031;
        }

        .btn.logout-btn:hover {
            background-color: #b71c1c;
        }

        .success, .error {
            margin: 10px 0;
            padding: 12px;
            font-size: 0.95rem;
            border-radius: 6px;
            text-align: center;
        }

        .success {
            background-color: #55efc4;
            color: #065f46;
        }

        .error {
            background-color: #fab1a0;
            color: #6d0303;
        }

        @media (max-width: 600px) {
            .dashboard-container {
                padding: 25px 20px;
            }

            h1 {
                font-size: 1.8rem;
            }

            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, Admin!</p>

        <?php if (isset($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <section>
            <h2>Manage Bookings</h2>
            <a href="manage_bookings.php" class="btn">Edit Bookings</a>
        </section>

        <section>
            <h2>Manage Users</h2>
            <a href="manage_users.php" class="btn">Edit Users</a>
        </section>

        <section>
            <h2>Logout</h2>
            <a href="admin_logout.php" class="btn logout-btn">Logout</a>
        </section>
    </div>
</body>
</html>

<?php
$conn->close();
?>
