
<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

$search = trim($_GET['search'] ?? '');

$sql = "SELECT
            registrations.id,
            registrations.student_name,
            registrations.student_id,
            registrations.department,
            registrations.email,
            registrations.phone,
            events.title AS event_name,
            registrations.created_at
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

// Download CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=CampusConnect_Registrations.csv');

$output = fopen('php://output', 'w');

fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Headings
fputcsv($output, [
    'Registration ID',
    'Student Name',
    'Student ID',
    'Department',
    'Email',
    'Phone',
    'Event Name',
    'Registration Date'
]);

// Data
while ($row = mysqli_fetch_assoc($result)) {

    $values = [
        $row['id'],
        $row['student_name'],
        $row['student_id'],
        $row['department'],
        $row['email'],
        $row['phone'],
        $row['event_name'] ?? 'Event deleted',
        $row['created_at']
    ];

    foreach ($values as &$value) {
        $value = (string)($value ?? '');

        if (preg_match('/^[\s]*[=+\-@]/', $value)) {
            $value = "'" . $value;
        }
    }

    unset($value);

    fputcsv($output, $values);
}

fclose($output);
exit();

?>
