
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

// Search functionality
$search = trim($_GET['search'] ?? '');

$sql = "SELECT
            registrations.id,
            registrations.student_name,
            registrations.email,
            registrations.phone,
            registrations.student_id,
            registrations.department,
            registrations.created_at,
            events.title AS event_name
        FROM registrations
        LEFT JOIN events
        ON registrations.event_id = events.id";

if ($search !== '') {

    $sql .= " WHERE
              registrations.student_name LIKE ?
              OR registrations.student_id LIKE ?
              OR registrations.email LIKE ?
              ORDER BY registrations.id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    $searchTerm = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} else {

    $sql .= " ORDER BY registrations.id DESC";

    $result = mysqli_query($conn, $sql);
}

$totalRegistrations = mysqli_num_rows($result);

$deleted = isset($_GET['deleted']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registrations | CampusConnect</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <div class="navbar">

        <div class="logo">CampusConnect Admin</div>

        <nav>
            <a href="index.php">Home</a>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_events.php">Manage Events</a>
            <a href="view_registrations.php">View Registrations</a>
            <a href="admin_logout.php">Logout</a>
        </nav>

    </div>

</header>

<section class="registration-section">

    <div class="registration-box registrations-box">

        <h1>Student Registrations</h1>

        <?php if ($deleted): ?>
            <p style="color:green; font-weight:bold;">
                Registration deleted successfully!
            </p>
        <?php endif; ?>

        <!-- Search Form -->

        <form method="GET" action="view_registrations.php"
              class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search by Student Name, ID or Email"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">Search</button>

            <a href="view_registrations.php" class="reset-search">
                Reset
            </a>

            <a href="export_registrations.php?search=<?php echo urlencode($search); ?>"
               class="export-btn">
                Download Excel
            </a>

        </form>

        <p>
            Total Registrations Found:
            <strong><?php echo $totalRegistrations; ?></strong>
        </p>

        <div class="table-wrapper">

            <table class="registrations-table">

                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Event Name</th>
                    <th>Registration Date</th>
                    <th>Action</th>
                </tr>

                <?php if ($totalRegistrations > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $row['id']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['student_name'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['student_id'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['department'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['email'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['phone'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['event_name'] ?? 'Event deleted'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['created_at'] ?? ''); ?>
                            </td>

                            <td>

                                <form method="POST"
                                      action="delete_registration.php"
                                      onsubmit="return confirm('Are you sure you want to delete this registration?');">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo (int)$row['id']; ?>"
                                    >

                                    <button type="submit"
                                            class="delete-registration-btn">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="9">
                            No matching student registrations found.
                        </td>
                    </tr>

                <?php endif; ?>

            </table>

        </div>

        <br>

        <a href="admin_dashboard.php">
            ← Back to Dashboard
        </a>

    </div>

</section>

</body>
</html>
