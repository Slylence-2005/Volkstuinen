<?php
session_start();
include '..\..\Backend\DatabaseContext\Database.php';

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = Database::GetConnection();

    $stmt = $pdo->prepare("SELECT UserType FROM users WHERE Id = ?");
    $stmt->execute([ (int)$_SESSION['user_id'] ]);
    $userType = $stmt->fetchColumn();

    if (!$userType || !in_array((int)$userType, [4])) {
        header("Location: login.php");
        exit();
    }
} catch (Exception $e) {
    // Optional: log error $e->getMessage()
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_type'] ?? null; // Avoid undefined index warning

// Get PDO connection
$conn = Database::GetConnection();

// Corrected query - no join needed
$sql = "SELECT Email, Usertype, Name FROM users WHERE id = :user_id";

$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

// Fallback to email if naam is not set
$naam = htmlspecialchars($user['Name'] ?? $user['Email']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkstuin Vereniging Sittard</title>
    <link rel="stylesheet" href="CSS-Admin/dashboard.css">
    <!-- javascript library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- javascript link -->
    <script src="dashboard.js" defer></script>

</head>
<body>
    <div class="sidebar">
    <img src="../../Frontend/Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">
    <div class="Icoontjes">

        <a href="dashboard.php">
            <div class="icon1">
                <img src="../Gedeeld/pictures/HomeMenuButton.svg" alt="huisknop">
            </div>
        </a>
        <a href="../../Frontend/Admin/GebruikerInfo.php">
            <div class="icon2">
                <img src="../Gedeeld/pictures/UserMenuButton.svg" alt="settings">
            </div>
        </a>
        <a href="../../Frontend/admin/aanvragen_beheer.php">
            <div class="icon2">
                <img src="../Gedeeld/pictures/persoonsgegevens.png" alt="settings">
            </div>
        </a>
        <a href="../../Frontend/admin/Pending_changes.php">
            <div class="icon2">
                <img src="../Gedeeld/pictures/UserMenuButton.svg" alt="settings">
            </div>
        </a>
        <a href="../../Frontend/admin/wachtrij_beheer.php">
            <div class="icon2">
                <img src="../Gedeeld/pictures/UserMenuButton.svg" alt="settings">
            </div>
        </a>
        <a href="../../Backend/logout.php">
            <div class="icon3">
                
                <img src="../Gedeeld/pictures/ExitMenuButton.svg" alt="uitloggen">
            </div>
        </a>
    </div>

  </div>
    
    <div class="header">VOLKSTUIN VERENIGING SITTARD</div>
    <div class="container">
        <div class="menu">
            <h4 style="color: #fff; text-align:center;">Menu</h4>
            <?php if ($role == 'admin') { ?>
                <a href="aanvragenbeheer.php"><i class="fas fa-file-alt"></i> Aanvragenbeheer</a>
                <a href="ledenbeheer.php"><i class="fas fa-users"></i> Ledenbeheer</a>
                <a href="pending_wijzigingen_beheer.php"><i class="fas fa-user-edit"></i> Beheer Wijzigingen</a>
                <a href="mededelingen_beheer.php"><i class="fas fa-bullhorn"></i> Mededeling Toevoegen</a>
            <?php } ?><?php if ($role == 'deelnemer') { ?>
                <a href="aanvragen.php"><i class="fas fa-plus-circle"></i> Aanvraag Volkstuin</a>
                <a href="persoonsgegevens.php"><i class="fas fa-user"></i> Mijn Persoonsgegevens</a>
                <a href="dashboard.php"><i class="fas fa-info-circle"></i> Mijn Aanvraag Status</a>
            <?php } ?>

        </div>
        <div class="content">
            <h2>Welkom terug, <?php echo $naam; ?> 👋</h2>
            <p>Je bent ingelogd als: <strong><?php echo ucfirst($role); ?></strong></p>
            <p>Selecteer een menuoptie aan de linkerkant om verder te gaan.</p>

            <?php if (in_array($role, ['admin'])) { ?>
                <a href="mijn_gebruikersgegevens.php" class="btn btn-warning mt-3">
                    <i class="fas fa-user-cog"></i> Mijn Gegevens Aanpassen
                </a>
            <?php } ?>

            <hr class="my-4">

            <?php
                $stmt = $conn->query("SELECT * FROM mededelingen ORDER BY datum DESC LIMIT 5");
                $mededelingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($mededelingen) > 0): ?>
                    <h4>📢 Mededelingen</h4>
                    <?php foreach ($mededelingen as $row): ?>
                        <div style="background:#444; padding:15px; margin-bottom:15px; border-left: 5px solid #7cb342; border-radius:8px;">
                            <strong style="font-size: 18px;"><?php echo htmlspecialchars($row['titel']); ?></strong><br>
                            <small class="text-muted"><?php echo date("d-m-Y H:i", strtotime($row['datum'])); ?></small>
                            <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($row['inhoud'])); ?></p>
                        </div>
                    <?php endforeach; ?>
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
</body>
</html>