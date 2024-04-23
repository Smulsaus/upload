<?php
session_start();

// Change these values to match your MySQL server configuration
$servername = "192.168.1.117"; // Change this to your database server
$username = "groep smul"; // Change this to your database username
$password = "Ms.oogway5"; // Change this to your database password
$dbname = "groep smul"; // Change this to your database name

// Check if the form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);

    // Execute query
    if ($stmt->execute()) {
        // Get result
        $result = $stmt->get_result();

        // Check if there is a result
        if ($result->num_rows > 0) {
            // User authenticated, set session variable and redirect
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username; // Set the username in session

            // Redirect the user to the profile page
            header("Location: ../php/feedbackpagina.php");
            exit();
        } else {
            // Invalid credentials
            // echo "Invalid username or password";
            // // Redirect the user back to index.html after 3 seconds
            // echo "<script>
            //         setTimeout(function() {
            //             window.location.href = 'index.html';
            //         }, 3000); // 3000 milliseconds = 3 seconden
            //       </script>";
        }
    } else {
        // Error executing query
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/hubpage.css">
    <meta http-equiv="Refresh" content="1; url='http://localhost/groep-smul/login/index.html'" />
    <title>Werkt niet</title>
    <style>
        h1,
        p {
            text-align: center;
            color: white;
        }

        #error {
            color: red;
            text-align: center;
        }

        .loader-container {
            position: relative;
            display: flex; /* Center horizontally */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 50vh; /* Ensure the container takes full height of the viewport */
        }

        .loader {
            border: 16px solid #757575;
            border-radius: 50%;
            border-top: 16px solid #032244;
            width: 90px;
            height: 90px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

    </style>
</head>
<body>
    <h1 id="error">Fout</h1> <!-- Corrected closing tag -->
    <h1>Verkeerde informatie</h1>
    <p>Probeer nog een keer</p>
    <div class="loader-container"> <!-- Added a parent container -->
        <div class="loader"></div>
    </div>
</body>
</html>
