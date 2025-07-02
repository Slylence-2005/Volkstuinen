<?php
require_once __DIR__ . "/../../Backend/SessionChecker.php";
require_once __DIR__ . "/../../Backend/DatabaseContext/Database.php";
checkSession($allowedUserTypes = [2]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volkstuin Vereniging Sittard</title>
  <link rel="stylesheet" href="CSS-Beheerder/Leden-beheer.css">
</head>
<body>
    
  <!-- Sidebar -->
  <div class="sidebar">
      <img src="../../Frontend/Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">
      <div class="Icoontjes">

          <a href="dashboard.php">
              <div class="icon1">
                  <img src="../Gedeeld/pictures/HomeMenuButton.svg" alt="huisknop">
              </div>
          </a>
            <a href="../../Frontend/Beheerder/GebruikerInfo.php">
                <div class="icon2">
                    <img src="../Gedeeld/pictures/UserMenuButton.svg" alt="Gebruiker Info">
                </div>  
            </a>
          <a href="../../Backend/logout.php">
              <div class="icon2">
                  <img src="../Gedeeld/pictures/ExitMenuButton.svg" alt="Uitloggen">
              </div>
          </a>
      </div>

  </div>

  <!-- Header -->
  <div class="header">
    VOLKSTUIN VERENIGING SITTARD
  </div>

  <!-- Lijst (main container) -->
  <div class="main-container">
    <h2>Leden Beheer</h2>
    <div class="leden-beheer-table">
          <table id="ledenTable">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Complex</th>
                <th>m²</th>
                <th>Email</th>
                <th>Tuin nummer</th>
            </tr>
            </thead>
            <tbody>
                
                <?php
            $conn = Database::GetConnection();

                // Fetch members
                $query = "SELECT Name, Complex, Email, GROUP_CONCAT(TuinNummer ORDER BY TuinNummer SEPARATOR ', ') AS TuinNummers 
                FROM users 
                GROUP BY Name, Email, Complex";
                $stmt = $conn->query($query);

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rows) {
            foreach ($rows as $row) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["Name"]) . "</td>
                    <td>" . htmlspecialchars($row["Complex"]) . "</td>
                    <td>?</td> <!-- Placeholder for m² -->
                    <td>" . htmlspecialchars($row["Email"]) . "</td>
                    <td>?</td>
                </tr>";
            }
            } else {
                echo "<tr><td colspan='5'>Geen leden gevonden.</td></tr>";
            }
                $conn = null;
                ?>

            </tbody>
        </thead>
      </table>
    </div>
  </div>
  <!-- javascript link -->
  <script src="Leden-beheer.js"></script>
</body>
</html>