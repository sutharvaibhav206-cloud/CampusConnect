
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("registrationForm");

    if (!form) return;

    form.addEventListener("submit", function (event) {

        const name = document.getElementById("student_name").value.trim();

        const email = document.getElementById("email").value.trim();

        const studentId = document.getElementById("student_id").value.trim();

        const department = document.getElementById("department").value.trim();

        if (!name || !email || !studentId || !department) {

            alert("Please fill all the fields.");

            event.preventDefault();

            return;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {

            alert("Please enter a valid email address.");

            event.preventDefault();

            return;
        }

    });

});
