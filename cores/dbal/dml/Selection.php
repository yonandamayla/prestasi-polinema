<?php

namespace app\cores\dbal\dml;

use app\cores\dbal\BaseConstruct;
use app\cores\dbal\DML;

class Selection extends BaseConstruct implements DML
{
    private string $columns;
    private string $sql = "";
    private array $params = [];


    public function __construct(string $columns)
    {
        $this->columns = $columns;
        $this->sql = "SELECT $columns";
    }

    public function setColumns(string ...$columns): self
    {
        $this->columns = implode(", ", $columns);
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function from(string $tableName, string $alias = null): self
    {
        $aliasTable = $this->handleAlias($alias);
        $this->sql = $this->sql . " " . trim("FROM [$tableName] $aliasTable") . ";";
        return $this;
    }

    public function distinct(): self
    {
        $this->sql = "SELECT DISTINCT $this->columns";
        return $this;
    }

    public function where(string $field, string $value, string $operator = "="): self
    {
        if ($value === "?") {
            $value = "?";
        } else if (is_string($value)) {
            $value = "'$value'";
        }

        $this->sql = str_replace(";", " ", $this->sql) . "WHERE $field $operator $value;";


        return $this;
    }

    public function and(string $field, string $value, string $operator = "="): self
    {
        $this->sql = str_replace(";", " ", $this->sql) . "AND $field $operator $value;";

        return $this;
    }

    public function or(string $field, string $value, string $operator = "="): self
    {
        $this->sql = str_replace(";", " ", $this->sql) . "OR $field $operator $value;";

        return $this;
    }

    public function innerJoin(string $referencedTable, string $alias = null): self
    {
        $aliasName = $this->handleAlias($alias);
        $this->sql = str_replace(";", " ", $this->sql) . "JOIN [$referencedTable] $aliasName";
        return $this;
    }

    public function rightJoin(string $referencedTable, string $alias = null): self
    {
        $aliasName = $this->handleAlias($alias);
        $this->sql = str_replace(";", " ", $this->sql) . "RIGHT JOIN [$referencedTable] AS $aliasName";
        return $this;
    }

    public function leftJoin(string $referencedTable, string $alias = null): self
    {
        $aliasName = $this->handleAlias($alias);
        $this->sql = str_replace(";", " ", $this->sql) . "LEFT JOIN [$referencedTable] AS $aliasName";
        return $this;
    }

    public function on(string $column1, string $column2): self
    {
        $this->sql = $this->sql . " ON $column1 = $column2;";

        return $this;
    }

    public function bindParams(string|int $params, mixed $value): self
    {
        $this->params[$params] = $value;

        return $this;
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    private function handleAlias(string|null $alias): string
    {
        return isset($alias) ? "AS $alias" : "";
    }

    /**
     * return boolean, only execute the query
     * @return bool
     */
    public function execute(): bool
    {
        return $this->executeSql($this->sql, $this->params);
    }

    /**
     * execute query and return the columns
     * @return array
     */
    public function fetch(): array
    {

        $res = $this->execute();

        if (!$res) {
            return [];
        }

        return $this->fetchColumn();
    }
}