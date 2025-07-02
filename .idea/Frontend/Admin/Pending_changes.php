<?php
session_start();
require_once '../../Backend/DatabaseContext/Database.php';



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


$pdo = Database::GetConnection();

// Goedkeuren of afwijzen
if ($_SESSION['user_type'] === 'admin' && isset($_GET['id'], $_GET['actie'])) {
    $id = $_GET['id'];
    $actie = $_GET['actie'];

    if ($actie === 'goedkeuren') {
        $stmt = $pdo->prepare("SELECT * FROM pending_changes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $wijziging = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($wijziging) {
            $user_id = $wijziging['user_id'];
            $nieuw_email = $wijziging['nieuw_email'];
            $nieuw_naam = $wijziging['nieuw_naam'];
            $nieuw_adres = $wijziging['nieuw_adres'];
            $nieuw_telefoon = $wijziging['nieuw_telefoon'];

            $pdo->prepare("UPDATE users SET email = :email WHERE id = :id")
                ->execute([':email' => $nieuw_email, ':id' => $user_id]);

            $pdo->prepare("UPDATE leden SET naam = :naam, adres = :adres, telefoon = :telefoon WHERE user_id = :user_id LIMIT 1")
                ->execute([
                    ':naam' => $nieuw_naam,
                    ':adres' => $nieuw_adres,
                    ':telefoon' => $nieuw_telefoon,
                    ':user_id' => $user_id
                ]);

            $pdo->prepare("UPDATE aanvragen_wijzigingen SET status = 'goedgekeurd' WHERE id = :id")
                ->execute([':id' => $id]);
        }
    } elseif ($actie === 'afwijzen') {
        $pdo->prepare("UPDATE aanvragen_wijzigingen SET status = 'afgewezen' WHERE id = :id")
            ->execute([':id' => $id]);
    }

    header("Location: pending_wijzigingen_beheer.php");
    exit();
}

// Ophalen pending wijzigingen
$stmt = $pdo->prepare("
    SELECT w.id, u.email, w.nieuw_email, w.nieuw_naam, w.nieuw_adres, w.nieuw_telefoon, w.datum, w.status
    FROM pending_changes w
    JOIN users u ON w.user_id = u.id
    WHERE w.status = 'in behandeling'
");

$stmt->execute();
$wijzigingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Pending Wijzigingen - VTV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2e2e2e;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background-color: #7cb342;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .content {
            padding: 40px 20px;
            width: 95%;
            margin: auto;
            background-color: #3e3e3e;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            overflow-x: auto;
        }
        h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            table-layout: auto;
            border-collapse: collapse;
            background-color: #4e4e4e;
            border-radius: 12px;
            overflow: hidden;
        }
        thead {
            background-color: #689f38;
        }
        th, td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            word-break: break-word;
        }
        tr:nth-child(even) {
            background-color: #5a5a5a;
        }
        tr:hover {
            background-color: #616161;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 14px;
        }
        .terug-btn {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            padding: 10px 25px;
            font-size: 16px;
            background-color: #7cb342;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
        }
        .terug-btn:hover {
            background-color: #689f38;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="header">VOLKSTUIN VERENIGING SITTARD</div>
    <div class="content">
        <h3>Pending Wijzigingen</h3>
        <table>
            <thead>
                <tr>
                    <th>Gebruiker</th>
                    <th>Nieuwe Email</th>
                    <th>Nieuwe Naam</th>
                    <th>Nieuwe Adres</th>
                    <th>Nieuwe Telefoon</th>
                    <th>Datum</th>
                    <?php if ($_SESSION['user_type'] === 'admin') echo "<th>Acties</th>"; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($wijzigingen)): ?>
                    <tr><td colspan="7">Geen pending wijzigingen gevonden.</td></tr>
                <?php else: ?>
                    <?php foreach ($wijzigingen as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['nieuw_email']) ?></td>
                            <td><?= htmlspecialchars($row['nieuw_naam']) ?></td>
                            <td><?= htmlspecialchars($row['nieuw_adres']) ?></td>
                            <td><?= htmlspecialchars($row['nieuw_telefoon']) ?></td>
                            <td><?= htmlspecialchars($row['datum']) ?></td>
                            <?php if ($_SESSION['user_type'] === 'admin'): ?>
                                <td>
                                    <a href="?id=<?= $row['id'] ?>&actie=goedkeuren" class="btn btn-success btn-sm">Goedkeuren</a>
                                    <a href="?id=<?= $row['id'] ?>&actie=afwijzen" class="btn btn-danger btn-sm">Afwijzen</a>
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
