<?php

session_start();
require_once "../Backend/Models/User.php";

$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "volkstuinen";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = ""; // Store error messages
$success_message = ""; // Store success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Password = password_hash($_POST['Password'], PASSWORD_BCRYPT);
    $PhoneNumber = $_POST['PhoneNumber'];
    $ZipCode = $_POST['ZipCode'];
    $Address = $_POST['Address'];
    $complex = NULL;
    $UserType = 1;

    // **Check if the username or email already exists**
    $check_sql = "SELECT Name, Email FROM users WHERE Name = ? OR Email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $Name, $Email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->bind_result($existingName, $existingEmail);
        $check_stmt->fetch();

        if ($existingName === $Name) {
            $error_message = "❌ Gebruikersnaam bestaat al. Kies een andere naam.";
        } elseif ($existingEmail === $Email) {
            $error_message = "❌ E-mailadres bestaat al. Gebruik een ander e-mailadres.";
        }
    } else {
        // **Insert new user if username and email are unique**
        $sql = "INSERT INTO users (Name, Email, Password, PhoneNumber, ZipCode, Address, Complex, UserType) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $Name, $Email, $Password, $PhoneNumber, $ZipCode, $Address, $complex, $UserType);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "✅ Registratie succesvol! Klik op de pijl om terug te gaan naar het loginscherm.";
            header("Location: register.php"); // Redirect to avoid form resubmission
            exit();
        } else {
            $error_message = "❌ Er is een fout opgetreden bij de registratie.";
        }

        $stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkstuin Vereniging Sittard</title>
    <link rel="stylesheet" href="register2.css">
    <style>
        .error-message, .success-message {
            font-weight: bold;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .error-message {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<div class="header">
    VOLKSTUIN VERENIGING SITTARD
</div>
<div class="main-container">

    <p class="Dashtitle">Registratie</p>
    <div class="content">
        <h2>Uw gegevens</h2>
        
        <?php 
        if (!empty($_SESSION['success_message'])) {
            echo '<div class="success-message">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']); // Remove message after displaying
        }
        ?>

        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="Name">Gebruikersnaam</label>
                    <input type="text" id="Name" name="Name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="Email">E-mailadres</label>
                <input type="email" id="Email" name="Email" required>
            </div>

            <div class="form-group">
                <label for="PhoneNumber">Telefoonnummer</label>
                <input type="text" id="PhoneNumber" name="PhoneNumber" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ZipCode">Postcode</label>
                    <input type="text" id="ZipCode" name="ZipCode" required>
                </div>
                <div class="form-group">
                    <label for="Address">Straatnaam en huisnummer</label>
                    <input type="text" id="Address" name="Address" required>
                </div>
            </div>

            <div class="form-group">
                <label for="Password">Wachtwoord</label>
                <input type="password" id="Password" name="Password" required>
            </div>

            <button type="submit">Registreren</button>
        </form>
    </div>
</div>

<div class="sidebar">
    <img src="../Frontend/Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">
    <div class="Icoontjes">
        <a href="../Frontend/login.php">
            <div class="icon3">
                <img src="../Frontend/Gedeeld/pictures/ExitMenuButton.svg" alt="Uitloggen">
            </div>
        </a>
    </div>
</div>

</body>
</html>
