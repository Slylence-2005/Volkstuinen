<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkstuin Vereniging Sittard</title>
    <link rel="stylesheet" href="wachtwoord-reset.css">
</head>
<body>

<div class="header">
    VOLKSTUIN VERENIGING SITTARD
</div>
<div class="main-container">

    <p class="Dashtitle"> Wachtwoord aanpassen</p>
    <div class="content">

    <form method="POST" action="">
            <div class="form-group">
                <label for="new_password">NIEUW WACHTWOORD</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">HERHAAL WACHTWOORD</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="submit-btn">Aanpassen</button>
        </form>

    </div>


    <div class="sidebar">
<img src="../Frontend/Gedeeld/pictures/logo-volkstuinverenigingsittard.png" alt="Logo">
    <div class="Icoontjes">

        <a href="dashboard.php">
            <div class="icon1">
                <img src="../Frontend/Gedeeld/pictures/HomeMenuButton.svg" alt="huisknop">
            </div>
        </a>
        <a href="../../Frontend/Gedeeld/GebruikerInfo.php">
            <div class="icon2">
                <img src="../Frontend/Gedeeld/pictures/UserMenuButton.svg" alt="settings">
            </div>
        </a>
        <a href="../../Frontend/login.php">
            <div class="icon3">
                
                <img src="../Frontend/Gedeeld/pictures/ExitMenuButton.svg" alt="uitloggen">
            </div>
        </a>
    </div>

</div>

</body>
</html>