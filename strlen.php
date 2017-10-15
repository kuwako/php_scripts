<?php
$input = trim(fgets(STDIN));

$tmp = $input;
// 末尾の.を抜く
$input = substr($tmp, 0, -1);

// 半角スペースでexplode
$exp = explode(' ', $input);
$totalStrCnt = 0;
foreach($exp as &$value) {
  if (substr($value, 0, 1) == '{') {
    // 先頭の文字が{ のものは{}の文字を抜いた上で,でexplodeし、かつ最大文字長のものを残す
    $value = getMaxLength($value);
  }

  $totalStrCnt += strlen($value);
}

// 各文字列の長さの平均を出力する
echo($totalStrCnt / count($exp));

function getMaxLength($text) {
  // 両端の{}削除
  $text = substr($text, 1, strlen($text) - 2);
  $wordArr = explode(',', $text);

  $wordCnt = 0;
  $maxWord = '';
  foreach($wordArr as $node) {
    if (strlen($node) > $wordCnt) {
      $wordCnt = strlen($node);
      $maxWord = $node;
    }
  }
  return $maxWord;
}
