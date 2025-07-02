<?php
session_start(); // Zorg dat sessie gestart is
require_once "../../Backend/DatabaseContext/Database.php"; // Laad de databaseclass

$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = Database::GetConnection();

    $user_id = $_SESSION['user_id'] ?? null;
    $complex_id = $_POST['complex_id'] ?? null;
    $tweede_keuze_id = $_POST['tweede_keuze_id'] ?? null;
    $motivatie = $_POST['motivatie'] ?? '';
    $datum = date("Y-m-d H:i:s");

    if (!$user_id || !$complex_id) {
        echo "<div style='text-align: center; color: red;'>Gebruiker of complex niet gespecificeerd.</div>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO aanvragen (user_id, complex_id, tweede_keuze_id, datum, opmerking)
            VALUES (:user_id, :complex_id, :tweede_keuze_id, :datum, :opmerking)
        ");

        $stmt->execute([
            ':user_id' => $user_id,
            ':complex_id' => $complex_id,
            ':tweede_keuze_id' => $tweede_keuze_id ?: null,
            ':datum' => $datum,
            ':opmerking' => $motivatie
        ]);

        $success = true;
    } catch (PDOException $e) {
        echo "<div style='text-align: center; color: red;'>Fout bij toevoegen: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// Laad alle complexen één keer als array
try {
    $pdo = Database::GetConnection();
    $complexen = $pdo->query("SELECT id, name FROM complexes")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div style='text-align: center; color: red;'>Fout bij ophalen van complexen: " . htmlspecialchars($e->getMessage()) . "</div>";
    $complexen = [];
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Aanvraag Volkstuin - VTV</title>
    <style>
        body { background-color: #2e2e2e; color: white; }
        .header { background-color: #7cb342; padding: 20px; text-align: center; font-size: 24px; font-weight: bold; }
        .form-container { display: flex; justify-content: center; padding: 50px; }
        .form-box {
            background-color: #3e3e3e;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 700px;
        }
        .form-label, .form-control, textarea {
            color: white;
            background-color: #4e4e4e;
            border: none;
        }
        .form-control:focus, textarea:focus {
            background-color: #5e5e5e;
            color: white;
        }
        .btn-success {
            background-color: #7cb342;
            border: none;
        }
        .btn-success:hover {
            background-color: #689f38;
        }
        .terug-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #7cb342;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
    </style>
</head>
<body>

<div class="header">VOLKSTUIN VERENIGING SITTARD</div>
<div class="form-container">
    <div class="form-box">
        <h3>Aanvraagformulier Volkstuin</h3>

        <?php if ($success): ?>
            <div style="text-align: center; color: green;">Aanvraag succesvol verzonden!</div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Voorkeurscomplex *</label>
                <select name="complex_id" class="form-control" required>
                    <option value="">-- Kies een complex --</option>
                    <?php foreach ($complexen as $row): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>">
                            <?= htmlspecialchars($row['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tweede keuze complex (optioneel)</label>
                <select name="tweede_keuze_id" class="form-control">
                    <option value="">-- Kies een tweede complex --</option>
                    <?php foreach ($complexen as $row): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>">
                            <?= htmlspecialchars($row['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Motivatie</label>
                <textarea name="motivatie" class="form-control" rows="4" placeholder="Waarom wilt u een volkstuin?"></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">Verzend aanvraag</button>
        </form>

        <a href="dashboard.php" class="terug-btn">← Terug naar Dashboard</a>
    </div>
</div>

</body>
</html>
