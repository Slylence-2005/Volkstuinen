<?php
require_once __DIR__ . "/../Backend/Models/User.php";

//tijdelijke pagina voor het Toevogen van nieuwe Gebruikers
$users = new User();

$users->Name = "Thomas";
$users->Email = "johndoe@gmail.com";
$users->Password = "LostMan123";
$users->PhoneNumber = "580-104-3438";
$users->ZipCode = "2141DB";
$users->Address = "Nethernowstreet 72";
$users->Complex = "1";
$users->UserType = "1"; 
$users->CreateUser();
//$users->Name = "Jeff Dober";
//$users->Email = "jeffdober@gmail.com";
//$users->Password = "Faker123";
//$users->PhoneNumber = "587-504-1438";
//$users->ZipCode = "6791BB";
//$users->Address = "westwaystreet 72";
//$users->CreateUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volkstuin Vereniging Sittard</title>
</head>
<body>

</body>
</html>
