<?php
require_once __DIR__ . "/../DBModel.php";
//Model Voor ParcelRequests extends DBModel aanpassingen in de database moeten hier ook overkomen
class ParcelRequests extends DBModel
{
    protected static string $table_name = "parcel-request";
    protected static string $primary_key = "Id";

        protected array $properties = [
            'Parcel'=> null,
            'Motive'=>null,
            'User'=> null,
            'Complex'=> null
        ];
}