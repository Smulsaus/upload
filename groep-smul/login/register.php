<?php
// Check if the form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $servername = "192.168.1.117"; // Change this to your database server
    $username = "groep smul"; // Change this to your database username
    $password = "Ms.oogway5"; // Change this to your database password
    $dbname = "groep smul"; // Change this to your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve data from form
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to check if email already exists
    $check_email_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);

    // Execute query to check if email already exists
    $check_email_stmt->execute();
    $result = $check_email_stmt->get_result();

    // Check if email already exists
    if ($result->num_rows > 0) {
        echo "Email already exists";
        // Redirect to register.html after 3 seconds
        header("refresh:3;url=register.html");
        exit(); // Stop further execution
    }

    // Prepare and bind SQL statement to insert new user
    $insert_stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $email, $username, $password);

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        // Redirect to register.html after 3 seconds
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'register.html';
                }, 3000); // 3000 milliseconds = 3 seconden
              </script>";
        exit(); // Stop further execution
    }

    // Execute query to insert new user
    $insert_stmt->execute();

    // Close statements and database connection
    $check_email_stmt->close();
    $insert_stmt->close();
    $conn->close();

    // Redirect to the profile page after successful registration
    header("Location: ../index.html");
    exit();
}
?>
