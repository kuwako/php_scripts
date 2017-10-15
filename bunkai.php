<?php
$input = trim(fgets(STDIN));

// 普通に総当たりしたらメモリがもたない..

$score = 0;

$min = PHP_INT_MAX;
for($i = 1; $i < strlen($input); $i++) {
  $score = calc($input, $i, $score);
  if ($score < $min) {
    $min = $score;
  }
}

echo($min . PHP_EOL);

function calc($input, $pos) {
  $score = 0;
  // 文字列の長さが1になったら終了
  if(strlen($input) <= 1) {
    return 0;
  }

  // 前と後ろに分ける
  $before = substr($input, 0, $pos);
  $after = substr($input, $pos);
  // スコア加算
  $score += strlen($before) * substr($before, -1, 1) + strlen($after) * substr($after, 0, 1);

  // 前と後ろにgetExpPosをする
  $score += calc($before, getExpPos($before) );
  $score += calc($after, getExpPos($after));

  return $score;
}

// scoreが最小の箇所で区切る
function getExpPos($input) {
  $expPos;
  $minSum = PHP_INT_MAX;

  for($i = 1; $i < strlen($input); $i++) {
    $before = substr($input, $i - 1, 1);
    $after = substr($input, $i, 1);
    // $sum = $before + $after;
    $sum = strlen($before) * substr($before, -1, 1) + strlen($after) * substr($after, 0, 1);

    if ($sum < $minSum) {
      $minSum = $sum;
      $expPos = $i;
    }
  }

  return ($expPos);
}
