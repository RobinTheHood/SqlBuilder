<?php
namespace RobinTheHood\SqlBuilder;

class SqlWhere extends SqlBuilder
{
    const OP_EQUAL = '=';
    const OP_LESS_THAN_OR_EQUAL = '<=';
    const OP_GREATER_THAN_OR_EQUAL = '>=';
    const OP_AND = 'AND';
    const OP_OR = 'OR';

    private $values;
    private $isSubWhere;
    private $subWheres;
    private $boolOperator;

    public function __construct($root, $isSubWhere = false, $operator = self::OP_AND)
    {
        $this->values = [];
        $this->root = $root;
        $this->isSubWhere = $isSubWhere;
        $this->subWheres = [];
        $this->boolOperator = $operator;
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

    public function subWhere($boolOperator = 'AND')
    {
        $operator = self::OP_AND;
        if ($boolOperator == 'OR') {
            $operator = self::OP_OR;
        }

        $subWhere = new SqlWhere($this->root, true, $operator);
        $this->subWheres[] = $subWhere;

        return $subWhere;
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
        $expressionSubWheres = $this->sqlExpressionSubWheres();
        $expressionValues = $this->sqlExpressionValues();

        if ($expressionSubWheres && $expressionValues) {
            $expression = $expressionSubWheres . ' ' . $this->boolOperator . ' ' . $expressionValues;
        } else {
            $expression = $expressionSubWheres . $expressionValues;
        }

        return $expression;
    }

    private function sqlExpressionSubWheres()
    {
        $count = 0;
        $expression = '';

        foreach($this->subWheres as $subWhere) {
            $expression .= '(' . $subWhere->sqlExpression() . ')';

            if (++$count < count($this->subWheres)) {
                $expression .=  ' ' . $this->boolOperator . ' ';
            }
        }

        return $expression;
    }

    private function sqlExpressionValues()
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
                $expression .=  $this->boolOperator . ' ';
            }
        }

        return $expression;
    }
}
