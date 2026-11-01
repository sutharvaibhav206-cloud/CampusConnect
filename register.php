
<?php

include 'config.php';

$error = "";
$success = "";

$event_id = isset($_GET['event_id'])
    ? (int) $_GET['event_id']
    : (int) ($_POST['event_id'] ?? 0);

// Get selected event
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, title, event_date, venue, capacity
     FROM events
     WHERE id = ? AND event_date >= CURDATE()"
);

mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$event) {
    die("Event not found or registration is closed.");
}

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['student_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $student_id = trim($_POST['student_id'] ?? '');
    $department = trim($_POST['department'] ?? '');

    // Validate student details
    if (
        $name == "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !preg_match('/^[0-9]{10}$/', $phone) ||
        $student_id == "" ||
        $department == ""
    ) {

        $error = "Please enter all details correctly.";

    } else {

        // Check duplicate registration
        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM registrations
             WHERE event_id = ? AND email = ?"
        );

        mysqli_stmt_bind_param(
            $check,
            "is",
            $event_id,
            $email
        );

        mysqli_stmt_execute($check);

        $duplicate = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($duplicate) > 0) {

            $error = "You are already registered for this event.";

        } else {

            // Check event capacity
            $countQuery = mysqli_prepare(
                $conn,
                "SELECT COUNT(*) AS total
                 FROM registrations
                 WHERE event_id = ?"
            );

            mysqli_stmt_bind_param(
                $countQuery,
                "i",
                $event_id
            );

            mysqli_stmt_execute($countQuery);

            $countResult = mysqli_stmt_get_result($countQuery);
            $countData = mysqli_fetch_assoc($countResult);

            if ($countData['total'] >= $event['capacity']) {

                $error = "Sorry! Event registration is full.";

            } else {

                // Insert student details into database
                $insert = mysqli_prepare(
                    $conn,
                    "INSERT INTO registrations
                    (event_id, student_name, email, phone,
                     student_id, department)
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                mysqli_stmt_bind_param(
                    $insert,
                    "isssss",
                    $event_id,
                    $name,
                    $email,
                    $phone,
                    $student_id,
                    $department
                );

                if (mysqli_stmt_execute($insert)) {

                    $success = "Registration successful!";

                } else {

                    $error = "Registration failed. Please try again.";

                }

                mysqli_stmt_close($insert);
            }

            mysqli_stmt_close($countQuery);
        }

        mysqli_stmt_close($check);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Event Registration | CampusConnect</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <div class="navbar">

        <div class="logo">CampusConnect</div>

        <nav>
            <a href="index.php">Home</a>
            <a href="index.php#events">Events</a>
            <a href="index.php#about">About</a>
        </nav>

    </div>

</header>

<section class="registration-section">

    <div class="registration-box">

        <h1>Event Registration</h1>

        <p class="selected-event">
            Event:
            <strong>
                <?php echo htmlspecialchars($event['title']); ?>
            </strong>
        </p>

        <p class="selected-event">
            Date:
            <?php echo date("d M Y", strtotime($event['event_date'])); ?>

            | Venue:
            <?php echo htmlspecialchars($event['venue']); ?>
        </p>

        <?php if ($error != ""): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <?php if ($success != ""): ?>

            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>

        <?php endif; ?>

        <?php if ($success == ""): ?>

        <form method="POST" id="registrationForm">

            <input type="hidden"
                   name="event_id"
                   value="<?php echo $event_id; ?>">

            <label>Full Name</label>

            <input type="text"
                   name="student_name"
                   id="student_name"
                   placeholder="Enter your full name"
                   maxlength="100"
                   required>

            <label>Email Address</label>

            <input type="email"
                   name="email"
                   id="email"
                   placeholder="Enter your email"
                   maxlength="150"
                   required>

            <label>Phone Number</label>

            <input type="tel"
                   name="phone"
                   id="phone"
                   placeholder="Enter your 10-digit phone number"
                   pattern="[0-9]{10}"
                   maxlength="10"
                   minlength="10"
                   required>

            <label>Student ID</label>

            <input type="text"
                   name="student_id"
                   id="student_id"
                   placeholder="Enter your student ID"
                   maxlength="50"
                   required>

            <label>Department</label>

            <input type="text"
                   name="department"
                   id="department"
                   placeholder="e.g. B.Tech CSE"
                   maxlength="100"
                   required>

            <button type="submit" class="register-submit">
                Submit Registration
            </button>

        </form>

        <?php endif; ?>

        <a href="index.php" class="back-home">
            ← Back to Home
        </a>

    </div>

</section>

<footer>

    <p>© 2026 CampusConnect. All Rights Reserved.</p>

</footer>

<script src="js/script.js"></script>

</body>
</html>
