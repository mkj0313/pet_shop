<?php

$keyword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];
}

// 関数ファイルを読み込む
require_once __DIR__ . '/functions.php';

// データベースに接続
$dbh = connect_db();

// SQL文の組み立て
$sql = <<<EOM
SELECT * FROM
    animals
WHERE description LIKE :description
EOM;

// プリペアドステートメントの準備
$stmt = $dbh->prepare($sql);

// バインドするパラメータの準備(更新情報の準備)
$description = '%' . $keyword . '%';

// パラメータのバインド
$stmt->bindParam(':description', $description, PDO::PARAM_STR);

// プリペアドステートメントの実行
$stmt->execute();

// 結果の受け取り
$animals = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO - INSERT</title>
</head>

<body>
    <h2>本日のご紹介ペット！</h2>
    <form action="" method="post">
        <div>
            <label for="keyword">キーワード</label>
            <input type="text" id="keyword" name="keyword">
            <input type="submit" value="検索">
        </div>
    </form>
    <!-- <ul> -->
    <?php foreach ($animals as $animal) : ?>
        <p><?= h($animal['type']) . 'の' . h($animal['classification']) . 'ちゃん' ?><br>
            <?= h($animal['description'])  ?><br>
            <?= h($animal['birthday']) . ' 生まれ'  ?><br>
            <?= '出生地 ' . h($animal['birthplace'])  ?><br>
        </p>
        <hr>
    <?php endforeach; ?>
    <!-- </ul> -->
</body>

</html>