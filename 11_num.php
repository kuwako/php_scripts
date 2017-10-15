<?php
fscanf(STDIN, "%d", $input);
$current = $input;
$result = '';
// TODO 方法は11でわる
echo(base_convert($input, 10, 11));
