<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $name = $_POST["username"];
    $comment = $_POST["comment"];

    // Establish a connection to MySQL
    $servername = "192.168.1.117"; // Replace with your MySQL server name
    $username = "groep smul"; // Replace with your MySQL username
    $password = "Ms.oogway5"; // Replace with your MySQL password
    $dbname = "groep smul"; // Replace with your MySQL database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to insert user information
    $sql = "INSERT INTO comments (username, comment) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $comment);
    if ($stmt->execute()) {
        // Redirect to a success page
        header("Location: feedbackpagina.php");
        exit();
    } else {
        // Failed to insert into the database
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to display comments
function displayComments($conn)
{
    $sql = "SELECT * FROM comments";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="comment">';
            echo '<h4>' . htmlspecialchars($row['username']) . '</h4>';
            echo '<p>' . nl2br(htmlspecialchars($row['comment'])) . '</p>';
            echo '</div>';
        }
    } else {
        echo "No comments yet.";
    }
}

// Establish a connection to MySQL
$servername = "192.168.1.117"; // Replace with your MySQL server name
$username = "groep smul"; // Replace with your MySQL username
$password = "Ms.oogway5"; // Replace with your MySQL password
$dbname = "groep smul"; // Replace with your MySQL database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



session_start();

// Controleren of de gebruiker is ingelogd
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
} else {
    header("Location: ../login/index.html");
    $username = "guest";
}

// Sessie vernietigen als de pagina wordt geopend in een nieuw tabblad
if (!isset($_SESSION['last_activity'])) {
    // Sessie is net gestart
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > 500) {
    // Meer dan 60 seconden inactiviteit, sessie vernietigen
    session_unset();
    session_destroy();
    header("Location: ../login/index.html"); // Redirect naar de inlogpagina
    exit();
} else {
    // Sessie is actief, bijwerken van de laatste activiteitstijd
    $_SESSION['last_activity'] = time();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../photos/zwaard logo.jpg">
    <title>Hub page</title>
    <link rel="stylesheet" type="text/css" href="../css/hubpage.css">
    <style>
        body {
          background-image: url('../photos/black-fog-or-smoke-color-isolated-background-for-effect-free-photo.jpg');
          background-size: cover;
          background-repeat: no-repeat;
          background-attachment: fixed;
          overflow: hidden; /* Disable scrolling by default */
        }

        .no-scroll {
            overflow: hidden; /* Disable scrolling */
        }

        .let-scroll {
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <a href="../index.html" class="fd-button">Hub</a>
        <a href="../html/website page.php" class="fb-button">Upload</a>
        <a href="../login/logout.php" class="logout-btn">Log out</a>
    </div>
    <div class="content">
        <h1 style="font-family: Copperplate, Copperplate Gothic Light, fantasy; color: aliceblue;">Feedbackpage</h1>
    </div>
    <div class="comment-section">
        <h2 style="font-family: copperplate, copperplate Gothic light,fantasy; color:aliceblue">Comments</h2>
        <form action="feedbackpagina.php" method="post">
            <label for="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" style="font-family: copperplate, copperplate Gothic light,fantasy;">Name:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly required>

            <label for="comment" style="font-family: copperplate, copperplate Gothic light,fantasy;">Comment:</label>
            <textarea id="comment" name="comment" required></textarea>
            
            <button type="submit" style="font-family: copperplate, copperplate Gothic light,fantasy;">Place Comment</button>
        </form>
    
        <br>
    
        <button onclick="toggleComments()" class="button">Show/Hide Comments</button>

        <div id="commentsContainer" style="display: none;">
            <?php displayComments($conn); ?>
        </div>
    </div>
    <script>
        function toggleComments() {
            var commentsContainer = document.getElementById('commentsContainer');
            if (commentsContainer.style.display === 'none') {
                commentsContainer.style.display = 'block';
                document.body.classList.remove("no-scroll"); // Enable scrolling
                document.body.classList.add("let-scroll"); // Enable scrolling
            } else {
                window.scrollTo(0, 0); // Scroll the page to the top
                commentsContainer.style.display = 'none';
                document.body.classList.add("no-scroll"); // Disable scrolling
                document.body.classList.remove("let-scroll"); // Disable scrolling
            }
        }
    </script>
</body>
</html>
