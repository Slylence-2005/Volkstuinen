<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkstuin Vereniging Sittard</title>
    <link rel="stylesheet" href="grondbeheer.css">
</head>
<body>
  <div class="container">
    <div class="header">VOLKSTUIN VERENING SITTARD</div>
    <div class="content">
    <div class="sidebar">
    <img src="../../Frontend/Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">
    <div class="Icoontjes">

    <a href="../../Frontend/Deelnemer/dashboard.php">
            <div class="icon1">
                <img src="../Gedeeld/pictures/HomeMenuButton.svg" alt="huisknop">
            </div>
        </a>
        <a href="../../Frontend/Gedeeld/GebruikerInfo.php">
            <div class="icon2">
                <img src="../Gedeeld/pictures/UserMenuButton.svg" alt="gebruikerinfo">
            </div>
        </a>
        <a href="../../Frontend/login.php">
            <div class="icon2">
                <img src="../Gedeeld/pictures/ExitMenuButton.svg" alt="uitloggen">
            </div>
        </a>
    </div>

</div>
      <div class="image-container">
        <img src="../../Frontend/Gedeeld/pictures/Baandert1-800px.jpg" alt="Grondbeheer">
      </div>
      <div class="grondbeheer-section">
        <div class="grondbeheer-header">Grondbeheer</div>
        <div class="grondbeheer-content">
          <div class="table-container">
            <!-- Parcelen in Gebruik -->
            <div class="table">
              <div class="table-header">Parcelen in Gebruik</div>
              <?php

              session_start();
              require_once "../../Backend/Models/User.php";// Include database connection file

              $servername = "localhost";
              $username = "root"; // Change this to your database username
              $password = ""; // Change this to your database password
              $dbname = "volkstuinen";

              $conn = new mysqli($servername, $username, $password, $dbname);
              
              $sql = "SELECT * FROM parcel ";
              $result = $conn->query($sql);
              
              while ($row = $result->fetch_assoc()) {
                  echo "<div class='table-row'>";
                  echo "<div class='parcel-name'>" . htmlspecialchars($row['Name']) . "</div>";
                  echo "<div class='parcel-size'>" . htmlspecialchars($row['Size']) . " m²</div>";
                  echo "<div class='complex-name'>" . htmlspecialchars($row['Complex']) . "</div>";
                  echo "</div>";
              }
              ?>
            </div>

            <!-- Parcelen Niet in Gebruik -->
            <div class="table">
              <div class="table-header">Niet in Gebruik</div>
              <?php
              $sql = "SELECT * FROM parcel_free";
              $result = $conn->query($sql);
              
              while ($row = $result->fetch_assoc()) {
                  echo "<div class='table-row'>";
                  echo "<div class='parcel-size'>" . htmlspecialchars($row['Size']) . " m²</div>";
                  echo "<div class='parcel-size'>" . htmlspecialchars($row['Complex']) . " </div>";
                  echo "</div>";
              }
              
              ?>
            </div>
          </div>
          <!-- Aanvragen knop buiten de tabellen -->
          <a href="aanvraag-parceel.php">
          <div class="grondbeheer-buttons">
            <button class="btn btn-primary">Aanvragen</button>
          </div>
          </a>

        </div>
      </div>
    </div>
  </div>
</body>
</html>


