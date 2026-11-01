# CampusConnect – College Event Registration System

CampusConnect is a web-based College Event Registration System developed to simplify the management of college events and student registrations.

The system allows students to explore upcoming college events and register online. Administrators can manage events, view student registrations, search participant records, and download registration data.

## Features

### Student Module
- View available college events.
- View event details, dates, venues, and descriptions.
- Register for college events.
- Submit student details through an online registration form.

### Admin Module
- Secure admin login.
- Admin dashboard with event and registration statistics.
- Add, edit, and delete events.
- View all student registrations.
- Search registrations by student name, student ID, or email.
- Delete student registration records.
- Export registration records in CSV format compatible with Microsoft Excel.

## Technologies Used

| Technology | Purpose |
|---|---|
| HTML5 | Webpage structure |
| CSS3 | Styling and responsive design |
| JavaScript | Client-side interactions |
| PHP | Backend development |
| MySQL | Database management |
| XAMPP | Local development server |
| VS Code | Code editor |

## Project Structure

```text
CampusConnect/
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── config.php
├── config.example.php
├── index.php
├── register.php
├── admin_login.php
├── admin_dashboard.php
├── admin_logout.php
├── manage_events.php
├── add_event.php
├── edit_event.php
├── view_registrations.php
├── delete_registration.php
└── export_registrations.php
```

## Installation and Setup

1. Install XAMPP on your computer.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Clone or download this repository.
4. Place the project folder inside the `htdocs` directory.
5. Open phpMyAdmin at `http://localhost/phpmyadmin`.
6. Create a MySQL database named `college_event_system`.
7. Create the required tables: `events`, `registrations`, and `admins`.
8. Configure the database connection in `config.php` using `config.example.php` as a reference.
9. Open the project in your browser:

```text
http://localhost/CampusConnect/
```

**Note:** The database tables and admin account must be created before using the system. Database credentials are not included in this public repository.

## Database Design

The project uses three main entities:

- **Admin:** Stores administrator login information.
- **Event:** Stores event details such as title, category, venue, date, time, and capacity.
- **Registration:** Stores student information and the event for which the student registers.

One event can have multiple student registrations.

## Project Objectives

- To reduce manual event registration work.
- To provide a centralized platform for college events.
- To maintain student registration records.
- To make event management easier for administrators.
- To provide quick searching and data export facilities.

## Future Enhancements

- Student login and personal registration history.
- Email confirmation after registration.
- Online event attendance management.
- Improved event capacity and duplicate-registration validation.
- Online hosting and deployment.

## Developed For

**College Minor Project**

**Project Name:** College Event Registration System (CampusConnect)

**University:** Sir Padampat Singhania University, Udaipur, Rajasthan.

---

Thank you for visiting CampusConnect!