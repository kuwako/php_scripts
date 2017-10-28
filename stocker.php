<?php
// 今回はsqlインジェクション対策を実施しない
require_once './DbHelper.php';

if (isset($_GET['function'])) {
    $function = $_GET['function'];
} else {
    throwError();
}

switch ($function) {
case 'addstock':
    addstock();
    break;
case 'checkstock':
    checkstock();
    break;
case 'sell':
    sell();
    break;
case 'checksales':
    checksales();
    break;
case 'deleteall':
    deleteall();
    break;
default :
    throwError();
}
// 予期しない値（例：整数のところに 1.1 の入力）が入力された場合は
// エラーとして扱い、"ERROR"と出力してください。

function addstock() {
    $name = '';
    $amount = 1;

    if (isset($_GET['name'])) {
        $name = $_GET['name'];
    } else {
        throwError();
    }

    if (isset($_GET['amount'])) {
        $amount = $_GET['amount'];
        if (!preg_match('/^[0-9]+$/', $amount)) {
            // 整数チェック
            throwError();
        }
    }

    // 同名の商品があれば追加でupdate、なければinsert
    $pdo = getPdo();
    $sql = $pdo->prepare ("select * from items where name = '$name' ;");
    $sql->execute();
    $result = $sql->fetchAll();
    if (empty($result)) {
        $insert = $pdo->prepare("INSERT INTO items (id, name, amount) VALUES (NULL, '$name', $amount);");
        $insert->execute();
    } else {
        $amount += $result[0]['amount'];
        $update = $pdo->prepare("UPDATE items SET amount = $amount WHERE name = '$name' ;");
        $update->execute();
    }
}

function checkstock() {
    if (!isset($_GET['name'])) {
        // nameがなければ全部表示
        return checkall();
    }

    $name = $_GET['name'];

    $pdo = getPdo();
    $sql = $pdo->prepare ("select * from items where name = '$name';");
    $sql->execute();
    $result = $sql->fetchAll();
    if (empty($result)) {
        echo "$name: 0" . PHP_EOL;
    } else {
        echo "$name: " . $result[0]['amount'] . PHP_EOL;
    }

}

/**
 * nameが指定されない場合は、全ての商品の在庫の数を
 * nameをキーに昇順ソートして出力する。
 * 在庫が 0 のものは表示しない。
 */
function checkall() {
    $pdo = getPdo();
    $sql = $pdo->prepare('select * from items where amount > 0 order by name;');
    $sql->execute();
    foreach($sql->fetchAll() as $row) {
        echo $row['name'] . ": " . $row['amount'] . PHP_EOL;
    }
}

function sell() {
    $name = '';
    $amount = 1;
    $price = 0;

    if (!isset($_GET['name'])) {
        throwError();
    }
    if (isset($_GET['amount'])) {
        $amount = $_GET['amount'];
        if (!preg_match('/^[0-9]+$/', $amount)) {
            // 整数チェック
            throwError();
        }
    }
    if (isset($_GET['price'])) {
        $price = $_GET['price'];
    }

    $name = $_GET['name'];

    $pdo = getPdo();
    $sql = $pdo->prepare("select * from items where name = '$name';");
    $sql->execute();
    $result = $sql->fetchAll();

    if (empty($result)) {
        throwError();
    }

    $item = $result[0];
    $amountCalc = $item['amount'] - $amount;
    $salesCalc = $item['sales'] + $price * $amount;

    if ($amountCalc < 0) {
        throwError();
    }

    $sql = $pdo->prepare("update items set amount = $amountCalc, sales = $salesCalc where name = '$name';");
    $sql->execute();
}


function checksales() {
    $pdo = getPdo();
    $sql = $pdo->prepare ("select sum(sales) as sales from items;");
    $sql->execute();
    $result = $sql->fetchAll();

    if (empty($result)) {
        echo "sales: 0" . PHP_EOL;
    }

    $sales = $result[0]['sales'];
    $sales = ceil($sales * 100) / 100;

    echo "sales: $sales";
}

function deleteall() {
    $pdo = getPdo();
    $sql = $pdo->prepare ("delete from items;");
    $sql->execute();
}

function throwError($message = "ERROR") {
    echo "ERROR";
    exit;
}

