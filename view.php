<?php

$id = $_GET["id"];

function dbConnect()
{
    try {
        return $dbh = new PDO(
            'mysql:host=localhost;dbname=miniblog;charset=utf8',
            'root',
            'root',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );
    } catch (PDOException $e) {
        header('Content-Type:text/plain;charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
}

function view($id)
{
    $sql = "SELECT * FROM articles WHERE id=$id";
    $stmt = dbConnect()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

$data  = view($id);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事表示画面</title>
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.18.1/build/cssreset/cssreset-min.css" />
    <style>
        .container {
            padding: 16px;
        }

        header {
            background-color: #4040ff;
            padding: 16px;
        }

        h1 {
            color: #fff;
            font-size: 24px;
        }
    </style>
</head>

<body>
    <header>
        <h1>ミニブログ</h1>
    </header>
    <?php
    foreach ($data as $item) {
    }
    ?>
    <h2>タイトル</h2>
    <p><?php echo $item["title"]; ?></p>
    <p>本文</p>
    <p><?php echo $item["body"]; ?></p>
    <!-- <a href="article_list.php">記事一覧</a> -->

</html>
