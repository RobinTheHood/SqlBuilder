<?php
namespace SqlBuilder;

use SqlBuilder\SqlSelect;
use SqlBuilder\SqlInsert;

class SqlBuilder
{
    private $child;

    private $mappings;
    private $mappingIndex;

    public function __construct()
    {
        $this->mappings = [];
        $this->mappingIndex = 0;
    }

    public function select($params)
    {
        $this->child = new SqlSelect($this, $params);
        return $this->child;
    }

    public function insert()
    {
        $this->child = new SqlInsert($this);
        return $this->child;
    }

    public function nextMapping($value)
    {
        $index = 'v' . ++$this->mappingIndex;
        $this->mappings[] = [$index => $value];
        return $index;
    }

    public function sql()
    {
        return $this->child->sql();
    }

    public function getMappings()
    {
        return $this->mappings;
    }
}
