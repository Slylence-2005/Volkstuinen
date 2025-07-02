<?php
session_start();
require_once "../../Backend/DatabaseContext/Database.php"; // Zorg dat dit het PDO-object levert

ini_set('display_errors', 1);
error_reporting(E_ALL);

 //Check op admin rechten
 if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
     header("Location: login.php");
     exit();  }

$pdo = Database::GetConnection();
$melding = "";

// Verwijderen
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM mededelingen WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $melding = "🗑️ Mededeling succesvol verwijderd.";
    } catch (PDOException $e) {
        $melding = "❌ Fout bij verwijderen: " . htmlspecialchars($e->getMessage());
    }
}

// Bewerken - ophalen
$bewerken_id = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$bewerken_data = null;
if ($bewerken_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM mededelingen WHERE id = :id");
        $stmt->execute([':id' => $bewerken_id]);
        $bewerken_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $melding = "❌ Fout bij ophalen: " . htmlspecialchars($e->getMessage());
    }
}

// Bewerken - opslaan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_id']) && $_POST['update_id'] !== '') {
    $update_id = intval($_POST['update_id']);
    $titel = $_POST['titel'] ?? '';
    $inhoud = $_POST['inhoud'] ?? '';

    try {
        $stmt = $pdo->prepare("UPDATE mededelingen SET titel = :titel, inhoud = :inhoud WHERE id = :id");
        $stmt->execute([
            ':titel' => $titel,
            ':inhoud' => $inhoud,
            ':id' => $update_id
        ]);
        $melding = "✏️ Nieuwsbrief succesvol bijgewerkt.";
    } catch (PDOException $e) {
        $melding = "❌ Fout bij bewerken: " . htmlspecialchars($e->getMessage());
    }
}

// Toevoegen
if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($_POST['update_id'])) {
    $titel = $_POST['titel'] ?? '';
    $inhoud = $_POST['inhoud'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO mededelingen (titel, inhoud) VALUES (:titel, :inhoud)");
        $stmt->execute([
            ':titel' => $titel,
            ':inhoud' => $inhoud
        ]);
        $melding = "✅ Nieuwsbrief succesvol toegevoegd.";
    } catch (PDOException $e) {
        $melding = "❌ Fout bij toevoegen: " . htmlspecialchars($e->getMessage());
    }
}

// Ophalen alle mededelingen
try {
    $stmt = $pdo->query("SELECT * FROM mededelingen ORDER BY datum DESC");
    $mededelingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $melding = "❌ Fout bij ophalen mededelingen: " . htmlspecialchars($e->getMessage());
    $mededelingen = [];
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Beheer Mededelingen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #2e2e2e; color: white; padding: 40px; }
        .container { max-width: 900px; margin: auto; }
        .alert { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>📢 Mededelingen Beheren</h2>

    <?php if (!empty($melding)): ?>
        <div class="alert alert-info"><?php echo $melding; ?></div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($bewerken_data['id'] ?? ''); ?>">
        <div class="mb-3">
            <input type="text" name="titel" class="form-control" placeholder="Titel" required value="<?php echo htmlspecialchars($bewerken_data['titel'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <textarea name="inhoud" class="form-control" placeholder="Inhoud" required><?php echo htmlspecialchars($bewerken_data['inhoud'] ?? ''); ?></textarea>
        </div>
        <button type="submit" class="btn btn-<?php echo $bewerken_data ? 'warning' : 'success'; ?>">
            <?php echo $bewerken_data ? '✏️ Bewerken' : '➕ Toevoegen'; ?>
        </button>
        <?php if ($bewerken_data): ?>
            <a href="mededelingen_beheer.php" class="btn btn-secondary">Annuleer</a>
        <?php endif; ?>
        <a href="dashboard.php" class="btn btn-success">← Terug naar Dashboard</a>
    </form>

    <h4>📋 Alle Mededelingen</h4>
    <?php if (count($mededelingen) > 0): ?>
        <table class="table table-dark table-bordered">
            <thead>
            <tr>
                <th>Titel</th>
                <th>Datum</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($mededelingen as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['titel']); ?></td>
                    <td><?php echo date("d-m-Y H:i", strtotime($row['datum'])); ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">✏️</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je dit wilt verwijderen?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen mededelingen gevonden.</p>
    <?php endif; ?>
</div>
</body>
</html>
