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

// Allowed sort fields matching your DB columns
$allowedSortFields = ['Name', 'request_date', 'Parcel'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortFields) ? $_GET['sort'] : 'request_date';

$orderBy = "ORDER BY $sort ASC";

// Fetch data from waiting_list
$stmt = $conn->query("SELECT Name, Parcel, motive, requested_meters, request_date FROM waiting_list $orderBy");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtlijstbeheer - Volkstuin Vereniging Sittard</title>
    <link rel="stylesheet" href="CSS-Admin/dashboard.css"> <!-- Check path if needed -->
</head>
<body>

<div class="sidebar">
    <img src="../../Frontend/Bestuurder/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">
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
</div> <!-- CLOSE sidebar -->

<div class="header">VOLKSTUIN VERENIGING SITTARD</div>

<div class="main-container">
    <p class="Dashtitle">Wachtlijst Beheer</p>

    <div class="content">
        <form method="GET" style="margin-bottom: 20px;">
            <label for="sort">Sorteer op:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="Name" <?php if($sort == 'Name') echo 'selected'; ?>>Naam</option>
                <option value="Parcel" <?php if($sort == 'Parcel') echo 'selected'; ?>>Complex</option>
                <option value="request_date" <?php if($sort == 'request_date') echo 'selected'; ?>>Datum</option>
            </select>
        </form>

        <table border="1" cellpadding="10" cellspacing="0" class="data-table">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Complex</th>
                    <th>Meters Aangevraagd</th>
                    <th>Reden</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) > 0): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Parcel']) ?></td>
                            <td><?= htmlspecialchars($row['requested_meters']) ?></td>
                            <td><?= htmlspecialchars($row['motive']) ?></td>
                            <td><?= htmlspecialchars(date("d-m-Y", strtotime($row['request_date']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">Geen resultaten gevonden.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> <!-- CLOSE main-container -->

</body>
</html>
