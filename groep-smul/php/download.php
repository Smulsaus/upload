<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../css/hubpage.css">
<title>File Management</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 80%;
        margin: 50px auto;
    }
    h3 {
        margin-bottom: 10px;
    }
    .file-box {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
    .file-box a {
        text-decoration: none;
        color: #0066cc;
    }
    .file-box a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <?php
    // Database connection parameters
    $servername = "192.168.1.117"; // Replace with your MySQL server name
    $username = "groep smul"; // Replace with your MySQL username
    $password = "Ms.oogway5"; // Replace with your MySQL password
    $dbname = "groep smul"; // Replace with your MySQL database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to retrieve file paths by username
    function getFilePaths($conn, $username) {
        $sql = "SELECT file_path FROM uploaded_files WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $filePaths = array();
        while ($row = $result->fetch_assoc()) {
            $filePaths[] = $row['file_path'];
        }
        return $filePaths;
    }

    // Handle file listing and download
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
        $username = $_GET['username'];
        $filePaths = getFilePaths($conn, $username);
        if (!empty($filePaths)) {
            echo '<a href="../html/website page.php" class="logout-btn">Go Bsck</a>';
            echo "<h3>Websites uploaded by $username:</h3>";
            foreach ($filePaths as $filePath) {
                echo "<div class='file-box'>";
                echo "<a href='download.php?file=$filePath'>" . basename($filePath) . "</a>";
                echo "</div>";
            }
        } else {
            echo '<a href="../html/website page.php" class="logout-btn">Go Back</a>';
            echo "<p>No websites uploaded by $username.</p>";
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['file'])) {
        $filePath = $_GET['file'];
        // Set headers for file download
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($filePath));
        // Read the file and output its contents
        readfile($filePath);
        exit;
    }

    $conn->close();
    ?>
</div>
</body>
</html>
