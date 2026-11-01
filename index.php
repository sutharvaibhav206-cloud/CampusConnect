
<?php
include 'config.php';

// Fetch upcoming events
$sql = "SELECT * FROM events
        WHERE event_date >= CURDATE()
        ORDER BY event_date ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>CampusConnect | College Events</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- Navigation Bar -->

<header>
    <div class="navbar">

        <div class="logo">
            CampusConnect
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="#events">Events</a>
            <a href="#about">About</a>
            <a href="admin/login.php"
               class="login-btn">Admin Login</a>
        </nav>

    </div>
</header>


<!-- Hero Section -->

<section class="hero">

    <div class="hero-content">

        <h1>
            Discover Events.<br>
            Create Memories.
        </h1>

        <p>
            Explore exciting college events,
            workshops and competitions.
            Register online and be a part of
            your campus journey.
        </p>

        <a href="#events" class="main-btn">
            Explore Events
        </a>

    </div>

</section>


<!-- Events Section -->

<section class="events-section" id="events">

    <h2>Upcoming Events</h2>

    <p class="section-description">
        Discover what's happening on your campus.
    </p>

    <div class="event-container">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <?php while ($event = mysqli_fetch_assoc($result)): ?>

                <div class="event-card">

                    <h3>
                        <?php echo htmlspecialchars($event['title']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($event['description']); ?>
                    </p>

                    <div class="event-details">

                        <p>
                            📅
                            <?php echo date(
                                "d M Y",
                                strtotime($event['event_date'])
                            ); ?>
                        </p>

                        <p>
                            📍
                            <?php echo htmlspecialchars($event['venue']); ?>
                        </p>

                        <p>
                            👥 Capacity:
                            <?php echo (int)$event['capacity']; ?>
                        </p>

                    </div>

                    <a
                        href="register.php?event_id=<?php echo (int)$event['id']; ?>"
                        class="register-btn">

                        Register Now

                    </a>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No upcoming events available.</p>

        <?php endif; ?>

    </div>

</section>


<!-- About Section -->

<section class="about-section" id="about">

    <h2>About CampusConnect</h2>

    <p>
        CampusConnect is a college event registration
        platform that allows students to explore
        upcoming events and register online easily.
        It also helps administrators manage events
        and student registrations.
    </p>

</section>


<!-- Footer -->

<footer>

    <p>
        © 2026 CampusConnect.
        College Event Registration System.
    </p>

</footer>

</body>
</html>
