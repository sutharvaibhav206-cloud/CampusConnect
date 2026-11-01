
<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

// Delete event
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    // Check whether students have registered
    $check = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) AS total FROM registrations WHERE event_id = ?"
    );

    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);

    $checkResult = mysqli_stmt_get_result($check);
    $data = mysqli_fetch_assoc($checkResult);

    if ($data['total'] > 0) {
        header("Location: manage_events.php?error=registered");
        exit();
    }

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM events WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: manage_events.php?deleted=1");
    exit();
}

// Fetch all events
$result = mysqli_query(
    $conn,
    "SELECT * FROM events ORDER BY event_date ASC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Events | CampusConnect</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="navbar">
        <div class="logo">CampusConnect Admin</div>

        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="registration-section">

    <div class="registration-box">

        <h1>Manage Events</h1>

        <a href="add_event.php" class="register-submit">
            + Add New Event
        </a>

        <br><br>
         <?php if (isset($_GET['added'])): ?>
    <p style="color:green;">
        Event added successfully!
    </p>
<?php endif; ?>

      
<?php if (isset($_GET['updated'])): ?>
    <p style="color:green;">
        Event updated successfully!
    </p>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <p style="color:green;">
        Event deleted successfully!
    </p>
<?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <p style="color:green;">Event deleted successfully!</p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p style="color:red;">
                This event has registrations and cannot be deleted.
            </p>
        <?php endif; ?>

        <div style="overflow-x:auto;">

            <table border="1" cellpadding="10"
                   cellspacing="0" width="100%">

                <tr>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>

                <?php while ($event = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td>
                        <?php echo $event['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($event['title']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($event['category']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($event['event_date']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($event['venue']); ?>
                    </td>

                    <td>
                        <?php echo $event['capacity']; ?>
                    </td>

                    <td>
                        <a href="edit_event.php?id=<?php echo $event['id']; ?>">
                            Edit
                        </a>

                        |

                        <a href="manage_events.php?delete=<?php echo $event['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this event?');">
                            Delete
                        </a>
                    </td>

                </tr>

                <?php endwhile; ?>

            </table>

        </div>

        <br>

        <a href="admin_dashboard.php">← Back to Dashboard</a>

    </div>

</section>

</body>
</html>
