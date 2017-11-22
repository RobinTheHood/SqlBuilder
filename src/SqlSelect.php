<?php
namespace SqlBuilder;

use SqlBuilder\SqlWhere;

class SqlSelect
{
    private $params;
    private $table;
    private $where;
    private $orderBy;
    private $root;

    public function __construct($root, $params)
    {
        $this->root = $root;
        $this->params = $params;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function where()
    {
        $this->where = new SqlWhere($this->root);
        return $this->where;
    }

    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function sql()
    {
        $sql = "SELECT {$this->params} ";

        if ($this->table) {
            $sql .= "FROM `{$this->table}` ";
        }

        if ($this->where) {
            $sql .= $this->where->sql(true);
        }

        if ($this->orderBy) {
            $sql .= "ORDER BY `{$this->orderBy}` ";
        }

        return $sql;
    }
}
