<?php
declare(strict_types=1);
header("Content-Type: application/json");
require_once __DIR__ . '/Models/Message.php';
session_start();

$messages = new Message();

// Dit Stuurt de berichten naar de verschillende pagina's waardat nodig is met de verschillende types van info

switch ($_SESSION['user_type']) {
    case 1:
        try {
            $Secondary_SearchTerm = $_SESSION['user_id'];
            $results = $messages->Search("Receiver", $Secondary_SearchTerm);

            $formattedResults = array_map(function ($row) {
                return [
                    'Id' => $row['Id'],
                    'Subject' => $row['Subject'],
                    'Message' => $row['Message'],
                    'Receiver' => $row['Receiver'],
                    'Sender' => $row['Sender'],
                ];
            }, $results);

            echo json_encode(['success' => true, 'messages' => $formattedResults]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
    case 2:
        try {
            $Secondary_SearchTerm = $_SESSION['user_complex'];
            $results = $messages->Search("Complex", $Secondary_SearchTerm);

            $formattedResults = array_map(function ($row) {
                return [
                    'Id' => $row['Id'],
                    'Subject' => $row['Subject'],
                    'Message' => $row['Message'],
                    'Receiver' => $row['Receiver'],
                    'Sender' => $row['Sender'],
                ];
            }, $results);

            echo json_encode(['success' => true, 'messages' => $formattedResults]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
    case 3:
        try {
            $results = $messages->findAll();

            $formattedResults = array_map(function ($row) {
                return [
                    'Id' => $row['Id'],
                    'Subject' => $row['Subject'],
                    'Message' => $row['Message'],
                    'Receiver' => $row['Receiver'],
                    'Sender' => $row['Sender'],
                ];
            }, $results);

            // Send JSON response
            echo json_encode(['success' => true, 'messages' => $formattedResults]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
}