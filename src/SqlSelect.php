<?php
namespace RobinTheHood\SqlBuilder;

use RobinTheHood\SqlBuilder\SqlWhere;

class SqlSelect extends SqlBuilder
{
    private $params;
    private $table;
    private $where;
    private $orderBy;
    private $limit1 = null;
    private $limit2 = null;

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

    public function limit($limit1, $limit2 = null)
    {
        $this->limit1 = (int) $limit1;
        if ($limit2 != null) {
            $this->limit2 = (int) $limit2;
        }
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
            $sql .= "ORDER BY {$this->orderBy} ";
        }

        if ($this->limit1 != null && $this->limit2 != null) {
            $sql .= "LIMIT {$this->limit1}, {$this->limit2} ";
        } elseif ($this->limit1 != null) {
            $sql .= "LIMIT {$this->limit1} ";
        }

        return $sql;
    }
}
