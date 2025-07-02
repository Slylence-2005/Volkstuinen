<?php
require_once __DIR__ . "/DatabaseContext/Database.php";

class DBModel
{
    protected static string $table_name;
    protected static string $primary_key;
    protected static array $errors = [];
    protected array $properties = [];
    protected PDO $conn;
    //Onderdeel Van andere Functies
    public function __construct()
    {
        $this->conn = Database::GetConnection();
    }
    //Onderdeel Van andere Functies
    public function __set($property, $value)
    {
        $this->properties[$property] = $value;
    }
    //Onderdeel Van andere Functies
    public function __get($property)
    {
        return $this->properties[$property] ?? null;
    }
    //Onderdeel Van andere Functies
    protected static function executeQuery(string $sql, array $params = [])
    {
        $stmt = Database::GetConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //hall alles Van een Model op
    public function findAll(): array
    {
        $sql = "SELECT * FROM " . static::$table_name;
        $result = static::executeQuery($sql); 
        return $result ?? [];
    }
    //zoek met het Model naar plaatsen overeenkomen de Id(VB: Id = 1)
    public function findById($id): array|bool
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE " . static::$primary_key . " = :id LIMIT 1";
        $result = static::executeQuery($sql, [':id' => $id]);
        return !empty($result) ? $result[0] : false;
    }
    //zoek met het Model naar plaatsen overeenkomen met extra Term en waar het leeg i(VB: Naam = Thomas, Complex IS NULL)
    public function FindWhereEmpty( $secondary_key, $tertiary_key, $secondary_SearchTerm): array
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE " . $tertiary_key . " IS NULL";
        $result = static::executeQuery($sql); 
        return $result ?? [];
    }

    //zoek met het Model naar plaatsen overeenkomen met extra Term(VB: Naam = Thomas)
    public function Search( $secondary_key, $secondary_SearchTerm): array
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE " . $secondary_key . " = :secondary_SearchTerm";
        return static::executeQuery($sql, [':secondary_SearchTerm' => $secondary_SearchTerm]);
    }
    //zoek met het Model naar plaatsen overeenkomen met extra Term en nog een extra term(VB: Naam = Thomas, Complex = 12)
    public function SearchWithConstraints( $secondary_key, $tertiary_key, $tertiary_SearchTerm , $secondary_SearchTerm): array
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE " . $tertiary_key . " = :tertiary_SearchTerm AND " . $secondary_key . " = :secondary_SearchTerm";
        return static::executeQuery($sql, [':secondary_SearchTerm' => $secondary_SearchTerm,':tertiary_SearchTerm' => $tertiary_SearchTerm]);
    }
    public function SearchWhereEmpty( $secondary_key, $tertiary_key, $secondary_SearchTerm): array
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE " . $secondary_key . " = :secondary_SearchTerm AND " . $tertiary_key . " IS NULL";
        return static::executeQuery($sql, [':secondary_SearchTerm' => $secondary_SearchTerm,]);
    }
    //maakt met het Model een nieuwe Entity
    public function Create(): bool
    {
        $columns = array_keys($this->properties);
        $placeholders = array_map(fn($col) => ":$col", $columns);
        $sql = "INSERT INTO " . static::$table_name . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->conn->prepare($sql);

        foreach ($this->properties as $key => $value)
        {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }
    //past met het Model een Entity aan
    public function Update($id): bool
    {
        $columns = array_keys($this->properties);
        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", $columns));
        $sql = "UPDATE " . static::$table_name . " SET $setClause WHERE " . static::$primary_key . " = :id";
        $stmt = $this->conn->prepare($sql);

            foreach ($this->properties as $key => $value)
            {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
    //Delete met het Model een Entity
    public function Delete($id): bool
    {
        $sql = "DELETE FROM " . static::$table_name . " WHERE " . static::$primary_key . " = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
}