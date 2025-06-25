<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$host = 'mysql';
$user = 'root';
$password = 'root';
$db = 'travel';
$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user ID is provided
if (!isset($_GET['id'])) {
    die("User ID is required.");
}

$user_id = intval($_GET['id']);
$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);

    $update_sql = "UPDATE users SET firstname=?, middlename=?, lastname=?, phone=?, address=?, email=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssi", $firstname, $middlename, $lastname, $phone, $address, $email, $user_id);

    if ($stmt->execute()) {
        $successMessage = "User updated successfully!";
    } else {
        $errorMessage = "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        form input[type="text"],
        form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .success, .error {
            text-align: center;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
<div class="container">
    <h2>Edit User</h2>

    <?php if (!empty($successMessage)): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="firstname" placeholder="First Name" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
        <input type="text" name="middlename" placeholder="Middle Name" value="<?php echo htmlspecialchars($user['middlename']); ?>">
        <input type="text" name="lastname" placeholder="Last Name" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
        <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
        <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($user['address']); ?>">
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <button type="submit">Update User</button>
    </form>

    <a href="manage_users.php">← Back to Manage Users</a>
</div>
</body>
</html>