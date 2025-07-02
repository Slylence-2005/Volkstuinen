<?php
declare(strict_types=1);
require_once __DIR__ . "/Models/Parcel.php";

// Dit is voor het ophalen, Compileren en verstuuren van de Chart Info.

session_start();
header('Content-Type: application/json');

switch ($_SESSION['user_type']) {
    case 2:
        try {
            if (!isset($_SESSION['user_complex'])) {
                throw new Exception("Session Complex is not set. Ensure cookies are sent with the request.");
            }
            $secondary_SearchTerm = $_SESSION['user_complex'];
        
            $Data_Owned = 0;
            $Data_Unowned = 0;
        
            $parcel = new Parcel();
        
            $parcelsNotInUse = $parcel->SearchWhereEmpty("Complex", "User", $secondary_SearchTerm);
            $Data_Unowned = count($parcelsNotInUse);
        
            $parcelsTotal = $parcel->Search("Complex", $secondary_SearchTerm);
            $Data_Owned = count($parcelsTotal) - $Data_Unowned;
        
            echo json_encode([
                'In Gebruik' => $Data_Owned,
                'Buiten Gebruik' => $Data_Unowned
            ]);
        } catch (Exception $e) {
            error_log('Error in Chart_Data.php: ' . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            echo json_encode([
                'error' => 'An error occurred while fetching data: ' . $e->getMessage()
            ]);
        }
        break;
    case 3;
    try {
    
        $Data_Owned = 0;
        $Data_Unowned = 0;
    
        $parcel = new Parcel();
    
        $parcelsNotInUse = $parcel->FindWhereEmpty("User");
        $Data_Unowned = count($parcelsNotInUse);
    
        $parcelsTotal = $parcel->findAll();
        $Data_Owned = count($parcelsTotal) - $Data_Unowned;
    
        echo json_encode([
            'In Gebruik' => $Data_Owned,
            'Buiten Gebruik' => $Data_Unowned
        ]);
    } catch (Exception $e) {
        error_log('Error in Chart_Data.php: ' . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
        echo json_encode([
            'error' => 'An error occurred while fetching data: ' . $e->getMessage()
        ]);
    }
    break;
    default:
    
        break;
}