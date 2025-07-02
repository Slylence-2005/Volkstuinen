<?php
require_once __DIR__ . "/../DBModel.php";
//Model Voor Message extends DBModel aanpassingen in de database moeten hier ook overkomen
class Message extends DBModel
{
    protected static string $table_name = "messages";
    protected static string $primary_key = "Id";

    public array $properties = [
        'Receiver' => null,
        'Sender' => null,
        'Subject' => null,
        'Message' => null,
        'User'=> null,
        'Complex'=> null
    ];
}