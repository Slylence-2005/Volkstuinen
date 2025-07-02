<?php
require_once __DIR__ . "/../DBModel.php";
//Model Voor Complexes extends DBModel aanpassingen in de database moeten hier ook overkomen
class Complexes extends DBModel
{
    protected static string $table_name = "complexes";
    protected static string $primary_key = "Id";

    public array $properties = [
        'Id' => null,
        'Name' => null,
    ];
}