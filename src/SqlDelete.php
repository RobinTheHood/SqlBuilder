<?php
namespace RobinTheHood\SqlBuilder;

class SqlDelete extends SqlBuilder
{
    private $table;
    private $values;
    private $where;

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

    public function setValue($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    public function where()
    {
        $this->where = new SqlWhere($this->root);
        return $this->where;
    }

    public function sql()
    {
        $sql = "DELETE FROM `{$this->table}` ";

        if ($this->where) {
            $sql .= $this->where->sql(true);
        }

        return $sql;
    }
}
