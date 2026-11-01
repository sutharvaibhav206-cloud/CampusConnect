
<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $venue = trim($_POST['venue']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $capacity = intval($_POST['capacity']);

    if (
        $title == "" ||
        $category == "" ||
        $description == "" ||
        $venue == "" ||
        $event_date == "" ||
        $event_time == "" ||
        $capacity <= 0
    ) {

        $error = "Please fill all fields correctly.";

    } elseif ($event_date < date('Y-m-d')) {

        $error = "Please select today or a future date.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO events
            (title, category, description, venue,
             event_date, event_time, capacity)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $title,
            $category,
            $description,
            $venue,
            $event_date,
            $event_time,
            $capacity
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: manage_events.php?added=1");
            exit();

        } else {

            $error = "Unable to add event. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Event | CampusConnect</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <div class="navbar">

        <div class="logo">CampusConnect Admin</div>

        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_events.php">Manage Events</a>
            <a href="admin_logout.php">Logout</a>
        </nav>

    </div>

</header>

<section class="registration-section">

    <div class="registration-box">

        <h1>Add New Event</h1>

        <p>Enter the details of the college event.</p>

        <?php if ($error != ""): ?>

            <p style="color:red;">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Event Title</label>

            <input type="text"
                   name="title"
                   placeholder="Enter event name"
                   maxlength="150"
                   required>

            <label>Category</label>

            <select name="category" required>

                <option value="">Select Category</option>
                <option value="Workshop">Workshop</option>
                <option value="Sports">Sports</option>
                <option value="Technical">Technical</option>
                <option value="Cultural">Cultural</option>
                <option value="Seminar">Seminar</option>
                <option value="Competition">Competition</option>

            </select>

            <label>Description</label>

            <textarea name="description"
                      placeholder="Enter event description"
                      rows="4"
                      required></textarea>

            <label>Venue</label>

            <input type="text"
                   name="venue"
                   placeholder="Enter event venue"
                   maxlength="150"
                   required>

            <label>Event Date</label>

            <input type="date"
                   name="event_date"
                   min="<?php echo date('Y-m-d'); ?>"
                   required>

            <label>Event Time</label>

            <input type="time"
                   name="event_time"
                   required>

            <label>Maximum Participants</label>

            <input type="number"
                   name="capacity"
                   min="1"
                   placeholder="Enter capacity"
                   required>

            <button type="submit"
                    class="register-submit">
                Add Event
            </button>

        </form>

        <br>

        <a href="manage_events.php">
            ← Back to Manage Events
        </a>

    </div>

</section>

</body>
</html>
