<?php
session_start();
require_once 'C:\xampp\htdocs\volkstuinen\Backend\DatabaseContext\Database.php';

$conn = Database::GetConnection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userType = $_SESSION['user_type']; // 1 = deelnemer, hoger = beheerder/admin/etc.
$message = "";

// Ophalen huidige gegevens uit users-tabel
$stmt = $conn->prepare("SELECT * FROM users WHERE Id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Gebruiker niet gevonden.");
}

// Verwerken formulier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bewerken'])) {
    $email = trim($_POST['email']);
    $naam = trim($_POST['naam']);
    $adres = trim($_POST['adres']);
    $telefoon = trim($_POST['telefoon']);
    $datum = date("Y-m-d H:i:s");

    // Validatie
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Ongeldig e-mailadres.</div>";
    } elseif (!preg_match('/^[0-9]{6,15}$/', $telefoon)) {
        $message = "<div class='alert alert-danger'>Telefoonnummer moet uit 6-15 cijfers bestaan.</div>";
    } else {
        if ($userType == 1) {
            // Check of er al een in behandeling is
            $check = $conn->prepare("SELECT id FROM pending_changes WHERE user_id = ? AND status = 'in behandeling'");
            $check->execute([$user_id]);

            if ($check->rowCount() > 0) {
                $message = "<div class='alert alert-warning'>Je hebt al een wijzigingsverzoek in behandeling.</div>";
            } else {
                // Insert nieuwe wijziging aanvraag
                $insert = $conn->prepare("INSERT INTO pending_changes (user_id, nieuw_email, nieuw_naam, nieuw_adres, nieuw_telefoon, status, datum) VALUES (?, ?, ?, ?, ?, 'in behandeling', ?)");
                $insert->execute([$user_id, $email, $naam, $adres, $telefoon, $datum]);
                $message = "<div class='alert alert-success'>Je wijziging is in behandeling en wacht op goedkeuring.</div>";
            }
        } else {
            // Direct update voor hogere gebruikers
            $update = $conn->prepare("UPDATE users SET Email = ?, Name = ?, Address = ?, PhoneNumber = ? WHERE Id = ?");
            $update->execute([$email, $naam, $adres, $telefoon, $user_id]);
            $message = "<div class='alert alert-success'>Gegevens succesvol gewijzigd.</div>";

            // Na update nieuwe gegevens ophalen voor tonen
            $stmt = $conn->prepare("SELECT * FROM users WHERE Id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Profiel Wijzigen</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    #gegevensTabel th {
        width: 150px;
    }
</style>
</head>
<body class="container py-4">

<h1>Mijn gegevens</h1>

<?php if ($message) echo $message; ?>

<table class="table table-bordered" id="gegevensTabel">
    <tr><th>Email</th><td><?= htmlspecialchars($user['Email']) ?></td></tr>
    <tr><th>Naam</th><td><?= htmlspecialchars($user['Name']) ?></td></tr>
    <tr><th>Adres</th><td><?= htmlspecialchars($user['Address']) ?></td></tr>
    <tr><th>Telefoon</th><td><?= htmlspecialchars($user['PhoneNumber']) ?></td></tr>
</table>

<button id="wijzigBtn" class="btn btn-primary mb-3">Gegevens wijzigen</button>
<a href="dashboard.php" class="btn btn-secondary">Home</a>

<form method="post" id="bewerkForm" style="display:none;">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['Email']) ?>" class="form-control" required />
    </div>
    <div class="mb-3">
        <label for="naam" class="form-label">Naam</label>
        <input type="text" name="naam" id="naam" value="<?= htmlspecialchars($user['Name']) ?>" class="form-control" required />
    </div>
    <div class="mb-3">
        <label for="adres" class="form-label">Adres</label>
        <input type="text" name="adres" id="adres" value="<?= htmlspecialchars($user['Address']) ?>" class="form-control" required />
    </div>
    <div class="mb-3">
        <label for="telefoon" class="form-label">Telefoon</label>
        <input type="text" name="telefoon" id="telefoon" value="<?= htmlspecialchars($user['PhoneNumber']) ?>" class="form-control" required />
    </div>
    <button type="submit" name="bewerken" class="btn btn-success">Opslaan</button>
<a href="dashboard.php" class="btn btn-secondary">Home</a>
</form>


<script>
document.getElementById('wijzigBtn').addEventListener('click', function() {
    this.style.display = 'none';
    document.getElementById('bewerkForm').style.display = 'block';
});
document.getElementById('cancelBtn').addEventListener('click', function() {
    document.getElementById('bewerkForm').style.display = 'none';
    document.getElementById('wijzigBtn').style.display = 'inline-block';
});
</script>

</body>
</html>
