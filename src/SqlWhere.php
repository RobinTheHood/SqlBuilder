<?php
namespace RobinTheHood\SqlBuilder;

class SqlWhere extends SqlBuilder
{
    const OP_EQUAL = '=';
    const OP_LESS_THAN_OR_EQUAL = '<=';
    const OP_GREATER_THAN_OR_EQUAL = '>=';

    private $values;

    public function __construct($root)
    {
        $this->values = [];
        $this->root = $root;
    }

    public function lessThanOrEqual($column, $value)
    {
        return $this->setOperation($column, $value, self::OP_LESS_THAN_OR_EQUAL);
    }

    public function greaterThanOrEqual($column, $value)
    {
        return $this->setOperation($column, $value, self::OP_GREATER_THAN_OR_EQUAL);
    }

    public function equals($column, $value)
    {
        return $this->setOperation($column, $value, self::OP_EQUAL);
    }

    public function setOperation($column, $value, $operator)
    {
        $this->values[] = [
                'column' => $column,
                'value' => $value,
                'operator' => $operator
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
            } elseif ($value['operator'] == self::OP_LESS_THAN_OR_EQUAL) {
                $expression .=  "(`{$value['column']}` <= {$this->root->nextMapping($value['value'])}) ";
            } elseif ($value['operator'] == self::OP_GREATER_THAN_OR_EQUAL) {
                $expression .=  "(`{$value['column']}` >= {$this->root->nextMapping($value['value'])}) ";
            }

            if (++$count < count($this->values)) {
                $expression .=  "AND ";
            }
        }
        return $expression;
    }
}
