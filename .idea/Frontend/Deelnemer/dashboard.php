<?php
require_once __DIR__ . "/../../Backend/SessionChecker.php";
checkSession([1]); // Only allow user type 1
session_start();
$identifier = $_SESSION['identifier'] ?? 'Gebruiker'; // Get identifier from session


require_once "../../Backend/Models/User.php";// Include database connection file

$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "volkstuinen";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM parcel";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Volkstuin Vereniging Sittard</title>
  <link rel="stylesheet" href="CSS-Deelnemer/dashboard.css">
</head>
<body>

  <!-- Koptekst -->
  <div class="header">VOLKSTUIN VERENING SITTARD</div>

  <!-- Zijbalk -->
  <div class="sidebar">
    <img src="../Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">

    <div class="Icoontjes">
      <a href="dashboard.php">
        <div class="icon1">
          <img src="../Gedeeld/pictures/HomeMenuButton.svg" alt="Dashboard">
        </div>
      </a>
      <a href="../../Frontend/Deelnemer/PersonalInfo.php">
        <div class="icon2">
          <img src="../Gedeeld/pictures/persoonsgegevens.png" alt="Gebruikersinstellingen">
        </div>
      </a>
      <a href="../../Frontend/Deelnemer/Aanvraag_status.php">
        <div class="icon2">
          <img src="../Gedeeld/pictures/aanvraagstatus.png" alt="Aanvraag status">
        </div>
      </a> <a href="../../Frontend/Deelnemer/aanvragen.php">
        <div class="icon2">
          <img src="../Gedeeld/pictures/aanvraagstatus_deelnemer.png" alt="Aanvraag status">
        </div>
      </a>
      <a href="../../Backend/logout.php">
        <div class="icon3">
          <img src="../Gedeeld/pictures/ExitMenuButton.svg" alt="Uitloggen">
        </div>
      </a>
    </div>
  </div>

  <!-- Hoofdcontainer -->
  <div class="main-container">
    <div class="Dashtitle"><p>Hallo, <?= htmlspecialchars($identifier) ?></p></div>
    <div class="line1"></div>
    
    <div class="content">
      <div class="news-sectie">
        <div class="foto1">
          <img src="../Gedeeld/pictures/Baandert1-800px.jpg" alt="Tuin foto">
        </div>
      </div>

      <!-- News Sectie (hier komen alle notificaties) -->
        <div class="news-sectie">
            <h2 class="newstitle">News binnen complex</h2>
            <div class="notificaties" id="notificaties">
                <!-- komen hier te staan als je een stuurt, dus als je iets wilt aanpassen moet dat met deze class -->
                <?php
                    $result = $conn->query("SELECT * FROM mededelingen ORDER BY datum DESC LIMIT 5");
                    if ($result->num_rows > 0): ?>
                        <h4>📢 Mededelingen</h4>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div style="background:#444; padding:15px; margin-bottom:15px; border-left: 5px solid #7cb342; border-radius:8px;">
                                <strong style="font-size: 18px;"><?php echo htmlspecialchars($row['titel']); ?></strong><br>
                                <small class="text-muted"><?php echo date("d-m-Y H:i", strtotime($row['datum'])); ?></small>
                                <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($row['inhoud'])); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="text-align:center; margin-top:40px;">
                            <div style="font-size:50px; animation:bounce 1s infinite;">📭</div>
                            <p style="color: #aaa; font-size: 18px; margin-top: 10px;">
                                Er zijn momenteel geen mededelingen.
                            </p>
                        </div>
                  <?php endif; ?>
            </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
