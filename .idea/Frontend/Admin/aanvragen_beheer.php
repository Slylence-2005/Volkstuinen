<?php
session_start();
require_once '../../Backend/DatabaseContext/Database.php';

// Get PDO connection
$conn = Database::GetConnection();

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_type'];


$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_type'];

// Filter by query string
$filter = $_GET['filter'] ?? 'alle';
$whereDate = '';

switch ($filter) {
    case 'vandaag':
        $whereDate = "AND DATE(a.datum) = CURDATE()";
        break;
    case 'gisteren':
        $whereDate = "AND DATE(a.datum) = CURDATE() - INTERVAL 1 DAY";
        break;
    case 'week':
        $whereDate = "AND DATE(a.datum) >= CURDATE() - INTERVAL 7 DAY";
        break;
    default:
        $whereDate = '';
}

// Handle admin approval/denial actions
if ($role === 'admin' && isset($_GET['id']) && isset($_GET['actie'])) {
    $id = intval($_GET['id']);
    $actie = $_GET['actie'];
    $status = ($actie === 'goedkeuren') ? 'goedgekeurd' : 'afgewezen';

    // Update aanvraag status
    $stmt = $conn->prepare("UPDATE aanvragen SET status = :status WHERE id = :id");
    $stmt->execute([':status' => $status, ':id' => $id]);

    // If approved, update the leden table
    if ($status === 'goedgekeurd') {
        $stmt = $conn->prepare("SELECT user_id, complex_id FROM aanvragen WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($row = $stmt->fetch()) {
            $updateStmt = $conn->prepare("UPDATE leden SET complex_id = :complex_id WHERE user_id = :user_id");
            $updateStmt->execute([
                ':complex_id' => $row['complex_id'],
                ':user_id' => $row['user_id']
            ]);
        }
    }

    header("Location: aanvragenbeheer.php");
    exit();
}

// Fetch aanvragen for admin and beheerder
$sql = "
    SELECT 
        a.id, 
        u.email, 
        l.naam AS gebruiker_naam, 
        c1.naam AS complex_naam, 
        c2.naam AS tweede_naam, 
        a.datum, 
        a.status, 
        a.opmerking 
    FROM aanvragen a
    JOIN users u ON a.user_id = u.id
    LEFT JOIN leden l ON u.id = l.user_id
    JOIN complexen c1 ON a.complex_id = c1.id
    LEFT JOIN complexen c2 ON a.tweede_keuze_id = c2.id
    WHERE 1 $whereDate
    ORDER BY a.datum DESC
";

$stmt = $conn->query($sql);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Aanvragenbeheer - VTV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1e1e1e; color: white; }
        .header { background-color: #7cb342; padding: 20px; text-align: center; font-size: 28px; font-weight: bold; }
        .content { padding: 40px; max-width: 95%; margin: auto; background-color: #2e2e2e; border-radius: 12px; }
        table { min-width: 1000px; width: 100%; border-collapse: collapse; background-color: #3e3e3e; }
        thead { background-color: #689f38; }
        th, td { padding: 12px; text-align: center; }
        tr:nth-child(even) { background-color: #444; }
        tr:hover { background-color: #505050; }
        .btn-sm { padding: 6px 12px; font-size: 14px; }
        .terug-btn { display: block; margin: 30px auto 0; padding: 10px 25px; background-color: #7cb342; color: white; border-radius: 8px; text-decoration: none; }
        .terug-btn:hover { background-color: #689f38; }
        .status-nieuw { color: orange; font-weight: bold; }
        .status-goedgekeurd { color: #00e676; font-weight: bold; }
        .status-afgewezen { color: #ff5252; font-weight: bold; }
        .filterbar { text-align: center; margin-bottom: 20px; }
        .filterbar a { color: white; text-decoration: none; margin: 0 10px; font-weight: bold; }
        .filterbar a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">VOLKSTUIN VERENIGING SITTARD</div>
    <div class="content">
        <h3>Aanvragenbeheer</h3>

        <div class="filterbar">
            <span>Filter: </span>
            <a href="?filter=alle">Alle</a> |
            <a href="?filter=vandaag">Vandaag</a> |
            <a href="?filter=gisteren">Gisteren</a> |
            <a href="?filter=week">Afgelopen week</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Naam</th>
                    <th>Voorkeurscomplex</th>
                    <th>Tweede keuze</th>
                    <th>Datum</th>
                    <th>Motivatie</th>
                    <th>Status</th>
                    <?php if ($role === 'admin') echo "<th>Acties</th>"; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!$results): ?>
                    <tr><td colspan="8" style="color: red; text-align: center;">Geen aanvragen gevonden.</td></tr>
                <?php else: ?>
                    <?php foreach ($results as $row): ?>
                        <?php $statusClass = 'status-' . strtolower($row['status']); ?>
                        <tr>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['gebruiker_naam'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['complex_naam']) ?></td>
                            <td><?= htmlspecialchars($row['tweede_naam'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['datum']) ?></td>
                            <td><?= !empty($row['opmerking']) ? htmlspecialchars($row['opmerking']) : '-' ?></td>
                            <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <?php if ($role === 'admin'): ?>
                                <td>
                                    <a href="?id=<?= $row['id'] ?>&actie=goedkeuren" class="btn btn-success btn-sm">✔ Goedkeuren</a>
                                    <a href="?id=<?= $row['id'] ?>&actie=afwijzen" class="btn btn-danger btn-sm">✖ Afwijzen</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="terug-btn">← Terug naar Dashboard</a>
    </div>
</body>
</html>
