<?php

namespace app\cores;

class Schema
{
    public static function createTableIfNotExist(string $tableName, callable $callback): array
    {
        $blueprint = new Blueprint();
        $callback($blueprint);
        $query = $blueprint->getColumnsAndConstraints();
        $column = $query["columns"];
        $constraint = $query["constraints"];

        $tsql = "IF NOT EXISTS (
                SELECT *
                FROM sysobjects
                WHERE name = '$tableName' AND xtype = 'U'
            )
            BEGIN
                CREATE TABLE [$tableName] ($column
           ";

        if (!empty($constraint)) {
            $tsql .= ", $constraint";
        }

        $tsql .= ") END;";

        return $blueprint->execute($tsql);
    }

    public static function dropTableIfExist(string $tableName): array
    {
        $blueprint = new Blueprint();
        $tsql = "DROP TABLE IF EXISTS $tableName;";
        return $blueprint->execute($tsql);
    }

    public static function alterTable(string $tableName, callable $callback): array
    {
        $blueprint = new Blueprint($tableName);
        $callback($blueprint);
        $alteration = $blueprint->getAlterations();

        $tsql = "ALTER TABLE [{$alteration["table"]}]
                ADD FOREIGN KEY ([{$alteration["column"]}]) 
                REFERENCES [{$alteration["referenceTable"]}] ([{$alteration["referenceColumn"]}]) 
                ON DELETE {$alteration["onDelete"]} ON UPDATE {$alteration["onUpdate"]};";

        return $blueprint->execute($tsql);
    }
}