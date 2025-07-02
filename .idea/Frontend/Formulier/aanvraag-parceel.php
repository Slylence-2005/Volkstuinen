<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database credentials
    $servername = "localhost"; // Database server
    $username = "root";        // Database username
    $password = "";            // Database password
    $dbname = "volkstuinen";   // Database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    
    $reason = $_POST['reason']; // Reason for request

    // SQL query to insert the data into the 'requests' table
    $sql = "INSERT INTO requests (Motive) VALUES (?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $reason); // "ss" means both are strings
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();

    // Redirect or display success message (optional)
    echo "<p>Uw aanvraag is succesvol verstuurd!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grond Aanvragen - Volkstuin Vereniging Sittard</title>
  <link rel="stylesheet" href="aanvraag-parceel.css">
</head>
<body>
  
<div class="sidebar">
    <img src="../Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">

    <div class="Icoontjes">
      <a href="dashboard.php">
        <div class="icon1">
          <img src="../Gedeeld/pictures/HomeMenuButton.svg" alt="Dashboard">
        </div>
      </a>
      <a href="../../Frontend/Gedeeld/GebruikerInfo.php">
        <div class="icon2">
          <img src="../Gedeeld/pictures/UserMenuButton.svg" alt="Gebruikersinstellingen">
        </div>
      </a>
      <a href="../../Frontend/logout.php">
        <div class="icon3">
          <img src="../Gedeeld/pictures/ExitMenuButton.svg" alt="Uitloggen">
        </div>
      </a>
    </div>
  </div>

  <div class="header">VOLKSTUIN VERENING SITTARD</div>

  <div class="content">
    <h1>Grond aanvragen</h1>
    <div class="form-container">
      <form action="" method="POST">
        <label for="parcel">Parceel:</label>
        <select id="parcel" name="parcel" required>
          <option value="" disabled selected>Selecteer een Perceel</option>
          <?php
            // Connect to the database
            $servername = "localhost"; // Database server
            $username = "root";        // Database username
            $password = "";            // Database password
            $dbname = "volkstuinen";  // Database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch Complex data from 'parcel-free' table
            $sql = "SELECT * FROM parcel_free";
            $result = $conn->query($sql);

            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['Complex'] . '">' . $row['Complex'] . '</option>';
                }
            } else {
                echo '<option value="">Geen beschikbare percelen</option>';
            }

            // Close the connection
            $conn->close();
          ?>
        </select>

        <label for="reason">Reden voor aanvraag:</label>
        <textarea id="reason" name="reason" rows="4" placeholder="Beschrijf uw reden voor deze aanvraag" required></textarea>

        <button type="submit">Verstuur aanvraag</button>
        </center>
      </form>
    </div>
  </div>
</body>
</html>
