
<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_events.php");
    exit();
}

$id = intval($_GET['id']);

$error = "";

// Fetch existing event
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM events WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$event) {
    die("Event not found.");
}

// Update event
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

    } else {

        $update = mysqli_prepare(
            $conn,
            "UPDATE events
             SET title = ?,
                 category = ?,
                 description = ?,
                 venue = ?,
                 event_date = ?,
                 event_time = ?,
                 capacity = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "ssssssii",
            $title,
            $category,
            $description,
            $venue,
            $event_date,
            $event_time,
            $capacity,
            $id
        );

        if (mysqli_stmt_execute($update)) {

            mysqli_stmt_close($update);

            header("Location: manage_events.php?updated=1");
            exit();

        } else {

            $error = "Unable to update event.";
        }

        mysqli_stmt_close($update);
    }

    // Keep submitted values in the form
    $event['title'] = $title;
    $event['category'] = $category;
    $event['description'] = $description;
    $event['venue'] = $venue;
    $event['event_date'] = $event_date;
    $event['event_time'] = $event_time;
    $event['capacity'] = $capacity;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Event | CampusConnect</title>

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

        <h1>Edit Event</h1>

        <p>Update the event details below.</p>

        <?php if ($error != ""): ?>

            <p style="color:red;">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Event Title</label>

            <input type="text"
                   name="title"
                   value="<?php echo htmlspecialchars($event['title']); ?>"
                   maxlength="150"
                   required>

            <label>Category</label>

            <select name="category" required>

                <?php
                $categories = [
                    "Workshop",
                    "Sports",
                    "Technical",
                    "Cultural",
                    "Seminar",
                    "Competition"
                ];

                foreach ($categories as $cat):
                ?>

                    <option value="<?php echo $cat; ?>"
                        <?php echo ($event['category'] == $cat) ? 'selected' : ''; ?>>

                        <?php echo $cat; ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <label>Description</label>

            <textarea name="description"
                      rows="4"
                      required><?php echo htmlspecialchars($event['description']); ?></textarea>

            <label>Venue</label>

            <input type="text"
                   name="venue"
                   value="<?php echo htmlspecialchars($event['venue']); ?>"
                   maxlength="150"
                   required>

            <label>Event Date</label>

            <input type="date"
                   name="event_date"
                   value="<?php echo htmlspecialchars($event['event_date']); ?>"
                   required>

            <label>Event Time</label>

            <input type="time"
                   name="event_time"
                   value="<?php echo htmlspecialchars($event['event_time']); ?>"
                   required>

            <label>Maximum Participants</label>

            <input type="number"
                   name="capacity"
                   min="1"
                   value="<?php echo htmlspecialchars($event['capacity']); ?>"
                   required>

            <button type="submit"
                    class="register-submit">

                Update Event

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
