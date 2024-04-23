<?php
session_start();

// Controleren of de gebruiker is ingelogd
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
} else {
    $username = "guest";
    header("Location: ../login/index.html");
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
        }
    </style>
</head>
<body>
    <div class="button-container">
        <a href="../index.html" class="fb-button">Hub</a>
        <a href="../php/feedbackpagina.php" class="fd-button">Feedback</a>
        <a href="../login/logout.php" class="logout-btn">Log out</a>
    </div>
    <div class="container">
        <div class="section">
            <h2>Upload your Website</h2>
            <form action="../php/upload.php" method="post" enctype="multipart/form-data">
                <label for="file">Upload File:</label><br>
                <input type="file" name="file" id="file"><br><br>
                <label for="username" style="font-family: Copperplate Gothic, sans-serif;">Name:</label><br>
                <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly required><br><br>
                <input type="submit" value="Upload">
            </form>
        </div>
        <div class="section">
            <h2>Download your Website</h2>
            <form action="../php/download.php" method="get">
                <label for="username" style="font-family: Copperplate Gothic, sans-serif;">Name:</label><br>
                <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" required><br><br>
                <input type="submit" value="Lijst Bestanden">
            </form>
        </div>
    </div>
</body>
</html>
