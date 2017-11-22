<?php
require_once 'vendor/autoload.php';

use SqlBuilder\SqlBuilder;

echo "*** Test 1 ***\n";
$query = new SqlBuilder();
$query = $query->select('*')->setTable('user');
echo $query->sql() . "\n";


echo "*** Test 2 ***\n";
$query = new SqlBuilder();
$query = $query->select('*')->setTable('user')
    ->where();
echo $query->sql() . "\n";

echo "*** Test 3 ***\n";
$query = new SqlBuilder();
$query = $query->select('*')->setTable('user')
    ->where()
    ->equals('id', 1);
echo $query->sql() . "\n";


echo "*** Test 4 ***\n";
$query = new SqlBuilder();
$query = $query->select('*')->setTable('user')
    ->orderBy('name')
    ->where()
    ->equals('id', 1);
echo $query->sql() . "\n";

echo "*** Test 5 ***\n";
$query = new SqlBuilder();
$query = $query->select('*')->setTable('user')
    ->orderBy('name')
    ->where()
    ->equals('id', 1)
    ->equals('name', 'John');
echo $query->sql() . "\n";

print_r($query->getMappings());

echo "*** Test 6 ***\n";
$query = new SqlBuilder();
$query = $query->insert()->setTable('user');
echo $query->sql() . "\n";


echo "*** Test 7 ***\n";
$query = new SqlBuilder();
$query = $query->insert()->setTable('user')
    ->addValue('name', 'John')
    ->addValue('city', 'Bremen');
echo $query->sql() . "\n";

echo "*** Test 8 ***\n";
$value = "'test'\\";

$query = new SqlBuilder();
$query = $query->insert()->setTable('user')
    ->addValue('name', 'John')
    ->addValue('city', $value);
echo $query->sql() . "\n";
