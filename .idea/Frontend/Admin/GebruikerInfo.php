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
$user_id = $_SESSION['user_id'];
$succes = false;
$foutmelding = "";

// Gegevens ophalen
try {
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fout bij ophalen van gegevens: " . htmlspecialchars($e->getMessage()));
}

// Bijwerken gegevens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nieuwe_email = $_POST['email'] ?? '';
    $nieuw_wachtwoord = $_POST['wachtwoord'] ?? '';

    try {
        if (!empty($nieuwe_email)) {
            $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
            $stmt->execute([':email' => $nieuwe_email, ':id' => $user_id]);
            $_SESSION['email'] = $nieuwe_email;
            $succes = true;
        }

        if (!empty($nieuw_wachtwoord)) {
            if (strlen($nieuw_wachtwoord) < 6) {
                $foutmelding = "Wachtwoord moet minimaal 6 tekens zijn.";
            } else {
                $hashed = password_hash($nieuw_wachtwoord, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->execute([':password' => $hashed, ':id' => $user_id]);
                $succes = true;
            }
        }
    } catch (PDOException $e) {
        $foutmelding = "Fout bij bijwerken van gegevens: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Gegevens Aanpassen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2e2e2e;
            color: white;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #3a3a3a;
            padding: 30px;
            border-radius: 12px;
        }
        .btn-success {
            background-color: #7cb342;
            border: none;
        }
        .btn-success:hover {
            background-color: #689f38;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">👤 Mijn Gegevens Aanpassen</h2>

    <?php if ($succes): ?>
        <div class="alert alert-success">✅ Je gegevens zijn succesvol bijgewerkt.</div>
    <?php endif; ?>
    <?php if (!empty($foutmelding)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($foutmelding) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">E-mailadres</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="wachtwoord" class="form-label">Nieuw Wachtwoord (optioneel)</label>
            <input type="password" class="form-control" id="wachtwoord" name="wachtwoord"
                   placeholder="Laat leeg als je wachtwoord niet wilt wijzigen">
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Opslaan</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">Annuleren</a>
    </form>
</div>
</body>
</html>
