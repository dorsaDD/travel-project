<?php
require('DBconnection.php'); // Ensure you have a database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send'])) {
    // Sanitize user input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $location = mysqli_real_escape_string($conn, trim($_POST['location']));
    $guests = intval($_POST['guests']);
    $arrivals = $_POST['arrivals'];
    $leaving = $_POST['leaving'];

    // Validate dates to ensure they are in the correct format
    if (!DateTime::createFromFormat('Y-m-d', $arrivals) || !DateTime::createFromFormat('Y-m-d', $leaving)) {
        die('Invalid date format. Please provide dates in YYYY-MM-DD format.');
    }

    // Validate that the leaving date is after the arrivals date
    if (strtotime($leaving) <= strtotime($arrivals)) {
        die('The leaving date must be after the arrivals date.');
    }

    // Prepare the SQL query
    $query = "INSERT INTO bookings (name, email, phone, address, location, guests, arrivals, leaving) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssssssss", $name, $email, $phone, $address, $location, $guests, $arrivals, $leaving);

        if ($stmt->execute()) {
            echo "Booking successful! We'll contact you soon.";
        } else {
            echo "Failed to book your trip. Please try again later. Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>book</title>
   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   
<section class="header">
   <a href="user_dashboard.php" class="logo">WANDER</a>
   <nav class="navbar">
      <a href="user_dashboard.php">home</a>
      <a href="html/user_about.html">about</a>
      <a href="html/user_package.html">packages</a>
      <a href="book.php">book</a>
      <a href="user_booking.php">booking info</a>
      <a href="logout.php">logout</a>
   </nav>
   <div id="menu-btn" class="fas fa-bars"></div>
</section>

<div class="heading" style="background:url(images/booking-header.jpg) no-repeat">
   <h1>booking</h1>
</div>

<section class="booking">
   <h1 class="heading-title">book your trip!</h1>

   <?php if (isset($success_message)): ?>
      <p class="success-message"><?php echo $success_message; ?></p>
   <?php elseif (isset($error_message)): ?>
      <p class="error-message"><?php echo $error_message; ?></p>
   <?php endif; ?>

   <form action="book.php" method="post" class="book-form">
      <div class="flex">
         <div class="inputBox">
            <span>name :</span>
            <input type="text" placeholder="enter your name" maxlength="30" name="name" required>
         </div>
         <div class="inputBox">
            <span>email :</span>
            <input type="email" maxlength="50" placeholder="enter your email" name="email" required>
         </div>
         <div class="inputBox">
            <span>phone :</span>
            <input type="number" maxlength="10" min="0" max="9999999999" placeholder="enter your number" name="phone" required>
         </div>
         <div class="inputBox">
            <span>address :</span>
            <input type="text" maxlength="50" placeholder="enter your address" name="address" required>
         </div>
         <div class="inputBox">
            <span>location :</span>
            <input type="text" maxlength="50" placeholder="place you want to visit" name="location" required>
         </div>
         <div class="inputBox">
            <span>how many :</span>
            <input type="number" min="1" max="99" maxlength="2" placeholder="number of guests" name="guests" required>
         </div>
         <div class="inputBox">
            <span>arrivals :</span>
            <input type="date" name="arrivals" required>
         </div>
         <div class="inputBox">
            <span>leaving :</span>
            <input type="date" name="leaving" required>
         </div>
      </div>

      <input type="submit" value="submit" class="btn" name="send">
   </form>
</section>

<section class="footer">
   <div class="box-container">
      <div class="box">
         <h3>quick links</h3>
         <a href="user_dashboard.php"><i class="fas fa-angle-right"></i> home</a>
         <a href="html/user_about.html"><i class="fas fa-angle-right"></i> about</a>
         <a href="html/user_package.html"><i class="fas fa-angle-right"></i> package</a>
         <a href="book.php"><i class="fas fa-angle-right"></i> book</a>
      </div>
      <!-- Additional footer content -->
   </div>
   <div class="credit">created by <span>DORSA</span> | all rights reserved!</div>
</section>

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>
