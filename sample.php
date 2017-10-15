<?php

class Solution {
  private $result = [];
  public function add($key, $value) {
    echo($key.PHP_EOL);
  }
  public function get($key) {
    echo($key.PHP_EOL);
  }
  public function remove($key) {
    echo($key.PHP_EOL);
  }
  public function evict() {
  }
}

$operations = [
  'add 5 3',
  'add 1 2',
  'get 5',
  'evict',
  'get 1',
  'remove 5',
  'exit',
];


$solution = new Solution();

foreach ($operations as $operation) {
  // TODO operationの例外処理
  echo($operation);
  $order = explode(' ', $operation);
  switch($order[0]) {
    case 'add':
      $solution->add($order[1], [2]);
      break;
    case 'get':
      $solution->get($order[1]);
      break;
    case 'remove':
      $solution->remove($order[1]);
      break;
    case 'evict':
      $solution->evict();
      break;
    case 'exit':
      break;
    default:
      echo($operation);
  }
}
