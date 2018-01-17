<?php
namespace RobinTheHood\SqlBuilder;

class SqlWhere extends SqlBuilder
{
    const OP_EQUAL = '=';

    private $values;

    public function __construct($root)
    {
        $this->values = [];
        $this->root = $root;
    }

    public function equals($column, $value)
    {
        $this->values[] = [
                'column' => $column,
                'value' => $value,
                'operator' => self::OP_EQUAL
            ];
        return $this;
    }

    public function sql($self = false)
    {
        if ($this->root && $self === false) {
            return $this->root->sql();
        }

        $sql = 'WHERE ';
        $sql .= $this->sqlExpression();

        return $sql;
    }

    private function sqlExpression()
    {
        $count = 0;
        $expression = '';
        foreach($this->values as $value) {
            if ($value['operator'] == self::OP_EQUAL) {
                $expression .=  "(`{$value['column']}` = {$this->root->nextMapping($value['value'])}) ";
            }

            if (++$count < count($this->values)) {
                $expression .=  "AND ";
            }
        }
        return $expression;
    }
}
