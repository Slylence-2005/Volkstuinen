<?php
session_start();
//Dit Checkt of een gebruiker Op de Pagina mag zijn Dit moet altijd als eerste Runnen op elke pagina
function checkSession(array $allowedUserTypes = []): void
{

    $loginUrl = "../../Frontend/Login.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: $loginUrl");
        exit();
    }

    if (!empty($allowedUserTypes) && !in_array($_SESSION['user_type'], $allowedUserTypes)) {
        header("Location: $loginUrl");
        exit();
    }

    $timeout_duration = getenv('SESSION_TIMEOUT') ?: 3600;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: $loginUrl?timeout=true");
        exit();
    }


    $_SESSION['last_activity'] = time();


    session_regenerate_id(true);
}