
<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$eventQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");
$eventData = mysqli_fetch_assoc($eventQuery);

$regQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM registrations");
$regData = mysqli_fetch_assoc($regQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CampusConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="navbar">
        <div class="logo">CampusConnect Admin</div>

        <nav>
            <a href="index.php">Home</a>
            <a href="manage_events.php">Manage Events</a>
            <a href="view_registrations.php">View Registrations</a>
            <a href="admin_logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="admin-dashboard-section">

    <div class="admin-dashboard-box">

        <h1>Admin Dashboard</h1>
        <p class="dashboard-welcome">Welcome, Admin!</p>

        <div class="dashboard-cards">

            <div class="dashboard-card">
                <h3>Total Events</h3>
                <h2><?php echo $eventData['total']; ?></h2>
                <p>Events available in CampusConnect</p>
            </div>

            <div class="dashboard-card">
                <h3>Total Registrations</h3>
                <h2><?php echo $regData['total']; ?></h2>
                <p>Total students registered</p>
            </div>

        </div>

        <h2 class="quick-heading">Quick Actions</h2>

        <div class="dashboard-actions">
            <a href="manage_events.php">Manage Events</a>
            <a href="add_event.php">Add New Event</a>
            <a href="view_registrations.php">View Registrations</a>
        </div>

    </div>

</section>

</body>
</html>
