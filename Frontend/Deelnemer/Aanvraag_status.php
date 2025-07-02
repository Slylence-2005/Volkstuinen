<?php 
session_start();
require_once 'C:\xampp\htdocs\Volkstuinen\Backend\DatabaseContext\Database.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

try {
    $conn = Database::GetConnection(); // ✅ Get PDO connection

    $stmt = $conn->prepare("
    SELECT 
        a.status, a.datum, 
        c1.Name AS complex_naam, 
        c2.Name AS tweede_naam, 
        a.opmerking 
    FROM aanvragen a
    JOIN complexes c1 ON a.complex_id = c1.Id
    LEFT JOIN complexes c2 ON a.tweede_keuze_id = c2.Id
    WHERE a.user_id = :user_id
    ORDER BY a.id DESC LIMIT 1
");


    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $aanvraag = $stmt->fetch(); // ✅ Fetch as associative array (default from Database class)
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mijn Aanvraag Status</title>
    <link rel="stylesheet" href="CSS-Deelnemer\aanvraag_status.css">
<div class="header">VOLKSTUIN VERENIGING SITTARD</div>
    <div class="container">
        <h3 class="text-center mb-4">Mijn Aanvraag Status</h3>

        <?php if ($aanvraag): 
            $status = strtolower($aanvraag['status']);
            $statusClass = 'status-' . $status;
            $bannerClass = $statusClass . '-banner';
            $emoji = 'ℹ️';

            if ($status == 'goedgekeurd') {
                $emoji = '✅';
                $bericht = 'Je aanvraag is goedgekeurd! We nemen binnenkort contact met je op.';
            } elseif ($status == 'afgewezen') {
                $emoji = '❌';
                $bericht = 'Helaas, je aanvraag is afgewezen.';
            } else {
                $emoji = '⏳';
                $bericht = 'Je aanvraag wordt momenteel beoordeeld.';
            }
        ?>
        <div class="status-banner <?= $bannerClass ?>">
            <?= $emoji ?> <?= $bericht ?>
        </div>

        <table class="table table-dark table-bordered">
            <tr>
                <th>Status</th>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($aanvraag['status']) ?></td>
            </tr>
            <tr>
                <th>Datum Aanvraag</th>
                <td><?= htmlspecialchars($aanvraag['datum']) ?></td>
            </tr>
            <tr>
                <th>Voorkeurscomplex</th>
                <td><?= htmlspecialchars($aanvraag['complex_naam']) ?></td>
            </tr>
            <tr>
                <th>Tweede keuze</th>
                <td><?= htmlspecialchars($aanvraag['tweede_naam'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Opmerking</th>
                <td>
                    <?php if (!empty($aanvraag['opmerking'])): ?>
                        <details>
                            <summary>Je hebt een motivatie ingevuld. Klik om te bekijken.</summary>
                            <div style="margin-top:8px;"><?= nl2br(htmlspecialchars($aanvraag['opmerking'])) ?></div>
                        </details>
                    <?php else: ?>
                        Geen opmerking ingevuld.
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">Je hebt nog geen aanvraag ingediend.</div>
        <?php endif; ?>

        <a href="dashboard.php" class="btn-back">← Terug naar Dashboard</a>
    </div>