<?php
session_start();
require('DBconnection.php'); // Ensure this file handles database connection correctly

// If admin is already logged in, redirect to the dashboard
if (isset($_SESSION['admin'])) {
    header('Location: admin_dashboard.php');
    exit;
}

// Function to sanitize user input
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

// Handle login form submission
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if ($password === $admin['password']) {
                $_SESSION['admin'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $errorMessage = "Incorrect password.";
            }
        } else {
            $errorMessage = "No admin found with this email.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Error preparing the statement: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        :root {
            --blue: #2980b9;
            --blue-light: #3498db;
            --text-dark: #2c3e50;
            --background: #ecf0f1;
            --white: #ffffff;
            --error: #e74c3c;
        }

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            transition: all 0.2s ease-out;
            text-decoration: none;
        }

        body {
            font-size: 62.5%;
            background-color: var(--background);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 420px;
            padding: 30px;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .heading {
            font-size: 2.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: var(--text-dark);
        }

        .heading span {
            color: var(--blue);
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 1.4rem;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1.4rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 1.6rem;
            background-color: var(--blue);
            color: var(--white);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: var(--blue-light);
        }

        .error {
            color: var(--error);
            font-size: 1.3rem;
            margin-bottom: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <section class="login">
        <h2 class="heading"><span>Admin</span> Login</h2>

        <form class="login-form" action="admin_login.php" method="POST">
            <?php if ($errorMessage): ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>
    </section>
</body>
</html>
