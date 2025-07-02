<?php
declare(strict_types=1);
header("Content-Type: application/json");

require_once __DIR__ . '/Models/User.php';
require_once __DIR__ . '/Models/Parcel.php';

session_start();

if (!isset($_SESSION['user_type'])) {
    echo json_encode(['success' => false, 'error' => 'User type not defined in session']);
    exit;
}

try {
    $Parcel = new Parcel();
    $users = new User();
// Dit Stuurt de User samen met hun Parcellen naar de verschillende pagina's waardat nodig is met de verschillende types van info
    switch ($_SESSION['user_type']) {
        case 2:
            Beheerder($users, $Parcel);
            break;

        case 3:
            Bestuurder($users, $Parcel);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid user type']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}


function Beheerder(User $users, Parcel $Parcel): void {
    if (!isset($_SESSION['user_complex'])) {
        echo json_encode(['success' => false, 'error' => 'User complex not defined in session']);
        return;
    }
    $Rent =12;
    $Secondary_SearchTerm = $_SESSION['user_complex'];


    $resultsU = $users->SearchUsers("Complex", $Secondary_SearchTerm);

    $userParcels = [];
    foreach ($resultsU as $user) {
        $Secondary_SearchTerm = $user['Id'];
        $parcelData = $Parcel->Search("User", $Secondary_SearchTerm);
        $userParcels[] = [
            'Id' => $user['Id'],
            'Name' => $user['Name'],
            'Email' => $user['Email'],
            'PhoneNumber' => $user['PhoneNumber'],
            'ZipCode' => $user['ZipCode'],
            'Address' => $user['Address'],
            'Complex' => $user['Complex'],
            'M' => $parcelData[0]['Size'] ?? null
        ];
    }

    echo json_encode(['success' => true, 'Users' => $userParcels]);
}

function Bestuurder(User $users, Parcel $Parcel): void {
    if (!isset($_SESSION['user_complex'])) {
        echo json_encode(['success' => false, 'error' => 'User complex not defined in session']);
        return;
    }

    $Secondary_SearchTerm = $_SESSION['user_complex'];

    $resultsU = $users->SearchUsers("Complex", $Secondary_SearchTerm);
    $Rent =3;
    $userParcels = [];
    foreach ($resultsU as $user) {
        $Secondary_SearchTerm = $user['Id'];
        $parcelData = $Parcel->Search("User", $Secondary_SearchTerm);
        $price = $Rent;
        $userParcels[] = [
            'Id' => $user['Id'],
            'Name' => $user['Name'],
            'Email' => $user['Email'],
            'PhoneNumber' => $user['PhoneNumber'],
            'ZipCode' => $user['ZipCode'],
            'Address' => $user['Address'],
            'Complex' => $user['Complex'],
            'Membership' => $user['Membership'],
            'Payment' => $user['Payment'],
            'M' => $parcelData[0]['Size'] ?? null,
            'Costs' => $price
        ];
    }

    echo json_encode(['success' => true, 'Users' => $userParcels]);
}
