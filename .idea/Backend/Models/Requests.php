<?php
require_once __DIR__ . "/../DBModel.php";
//Model Voor Requests extends DBModel aanpassingen in de database moeten hier ook overkomen
class Requests extends DBModel
{
    protected static string $table_name = "requests";
    protected static string $primary_key = "Id";

    protected array $properties = [
        'Name' => null,
        'Email' => null,
        'PhoneNumber' => null,
        'ZipCode' => null,
        'Address' => null,
        'Motive' => null,
        'Complex1' => null,
        'Complex2' => null
    ];
}