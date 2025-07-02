<?php
session_start();
//dit stuurt de gebruiker naar hun eigen Homepagina.
if (isset($_SESSION['user_type'])) {
    $userType = $_SESSION['user_type'];

    switch ($userType) {
        case 4:
            header("Location:../Frontend/Admin/dashboard.php");
            exit;
        case 3:
            header("Location: /Frontend/Bestuurder/dashboard.php");
            exit;
        case 2:
            header("Location:../Frontend/Beheerder/dashboard.php");
            exit;
        case 1:
            header("Location:../Frontend/Deelnemer/dashboard.php");
            exit;
        default:
            header("Location:../Frontend/unknown.php");
            exit;
    }
} else {
    header("Location:../../Frontend/Login.php");
    exit;
}