<?php
namespace SqlBuilder;

class SqlInsert
{
    private $table;
    private $values;

    private $mappings;
    private $mappingIndex;

    public function __construct($root)
    {
        $this->root = $root;
        $this->values = [];
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function addValue($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    public function sql()
    {
        $count = 0;
        $columnNamesString = '';
        foreach ($this->values as $columnName => $value) {
            $columnNamesString .= "`$columnName`";

            // Add , to the string as long as it is not the last interation.
            if (++$count < count($this->values)) {
               $columnNamesString .= ', ';
            }
        }

        // Create column values string.
        $count = 0;
        $valuesString = '';
        foreach ($this->values as $columnName => $value) {
            $valuesString .= ":{$this->root->nextMapping($value)}";

            // Add , to the string as long as it is not the last interation.
            if (++$count < count($this->values)) {
               $valuesString .= ', ';
            }
        }

        $sql = "INSERT INTO `{$this->table}` ($columnNamesString) VALUES ($valuesString)";

        return $sql;
    }
}
