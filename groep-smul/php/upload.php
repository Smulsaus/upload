<?php
session_start();

// Controleren of de gebruiker is ingelogd
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
} else {
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
    header("Location: index.html"); // Redirect naar de inlogpagina
    exit();
} else {
    // Sessie is actief, bijwerken van de laatste activiteitstijd
    $_SESSION['last_activity'] = time();
}

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

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "../uploads/";
    // Generate unique file name
    $uniqueFileName = uniqid() . "_" . basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . $uniqueFileName;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (optional)
    if ($_FILES["file"]["size"] > 500000000) { // Adjust the file size limit as needed
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats (optional)
    if($fileType !== "zip" && $fileType !== "rar" && $fileType !== "tar") { // Adjust the allowed file formats as needed
        echo "Sorry, only ZIP, RAR, and TAR files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Insert the unique file path, identifier, and username into the database
            $filePath = $targetFile;
            $uniqueIdentifier = uniqid(); // Generate unique identifier
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : ''; // Get username from session
            $sql = "INSERT INTO uploaded_files (file_path, unique_identifier, username) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $filePath, $uniqueIdentifier, $username);
            
            if ($stmt->execute()) {
                echo "<p style='font-family: Copperplate Gothic, sans-serif; color: white; font-size: 24px; text-align: center; margin-top: 50vh; transform: translateY(-50%);'>File uploaded. You will be redirected.</p>";
            } else {
                echo "<p style='font-family: Copperplate Gothic, sans-serif; color: white; font-size: 24px; text-align: center; margin-top: 50vh; transform: translateY(-50%);'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
            
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Function to retrieve file path by unique identifier
function getFilePath($conn, $uniqueIdentifier) {
    $sql = "SELECT file_path FROM uploaded_files WHERE unique_identifier = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uniqueIdentifier);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['file_path'];
    } else {
        return false;
    }
}

// Handle file download
if (isset($_GET['download'])) {
    $uniqueIdentifier = $_GET['download'];
    $filePath = getFilePath($conn, $uniqueIdentifier);
    if ($filePath) {
        // Set headers for file download
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($filePath));
        // Read the file and output its contents
        readfile($filePath);
        exit;
    } else {
        echo "File not found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/hubpage.css">
    <meta http-equiv="Refresh" content="2; url='http://localhost/groep-smul/html/website%20page.php'" />
</head>
<body>
</body>
</html>