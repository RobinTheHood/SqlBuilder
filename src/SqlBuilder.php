<?php
namespace SqlBuilder;

use SqlBuilder\SqlSelect;
use SqlBuilder\SqlInsert;

class SqlBuilder
{
    protected $root;
    protected $child;

    private $mappings;
    private $mappingIndex;

    public function __construct()
    {
        $this->root = $this;
        $this->mappings = [];
        $this->mappingIndex = 0;
    }

    public function select($params)
    {
        $this->child = new SqlSelect($this->root, $params);
        return $this->child;
    }

    public function insert()
    {
        $this->child = new SqlInsert($this);
        return $this->child;
    }

    public function update()
    {
        $this->child = new SqlUpdate($this);
        return $this->child;
    }

    public function delete()
    {
        $this->child = new SqlDelete($this);
        return $this->child;
    }

    public function nextMapping($value)
    {
        $index = ':v' . ++$this->mappingIndex;
        $this->mappings[$index] = $value;
        return $index;
    }

    public function sql()
    {
        return $this->child->sql();
    }

    public function getMappings()
    {
        return $this->root->mappings;
    }
}
