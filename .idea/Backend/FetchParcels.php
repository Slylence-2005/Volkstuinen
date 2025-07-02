<?php
declare(strict_types=1);
header("Content-Type: application/json");
require_once __DIR__ . '/Models/Parcel.php';
session_start();

$Parcel = new Parcel();
// Dit Stuurt de Parcellen naar de verschillende pagina's waardat nodig is met de verschillende types van info
switch ($_SESSION['user_type']) {
    case 2:
    try {
        $Secondary_SearchTerm = $_SESSION['user_complex'];
        $results = $Parcel->Search("Complex", $Secondary_SearchTerm);

        $formattedResults = array_map(function ($row) {
            return [
                'Id' => $row['Id'],
                'Name' => $row['Name'],
                'Size' => $row['Size'],
                'User' => $row['User'],
            ];
        }, $results);

        echo json_encode(['success' => true, 'Parcel' => $formattedResults]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    break;
    case 3:
        try {
            $results = $Parcel->findAll();
    
            $formattedResults = array_map(function ($row) {
                return [
                'Id' => $row['Id'],
                'Name' => $row['Name'],
                'Size' => $row['Size'],
                'User' => $row['User'],
                ];
            }, $results);

            echo json_encode(['success' => true, 'Parcel' => $formattedResults]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
}