
<?php

session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, email, password_hash
         FROM admins
         WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $admin['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];

            header("Location: admin_dashboard.php");
            exit();

        } else {
            $error = "Incorrect email or password.";
        }

    } else {
        $error = "Incorrect email or password.";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | CampusConnect</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>
    <div class="navbar">

        <div class="logo">CampusConnect</div>

        <nav>
            <a href="index.php">Home</a>
        </nav>

    </div>
</header>

<section class="registration-section">

    <div class="registration-box">

        <h1>Admin Login</h1>

        <p>Login to manage college events.</p>

        <?php if ($error != ""): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>Email Address</label>

            <input type="email"
                   name="email"
                   placeholder="Enter admin email"
                   required>

            <label>Password</label>

            <input type="password"
                   name="password"
                   placeholder="Enter password"
                   required>

            <button type="submit" class="register-submit">
                Login
            </button>

        </form>

        <a href="index.php" class="back-home">
            ← Back to Home
        </a>

    </div>

</section>

</body>
</html>
