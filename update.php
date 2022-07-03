<?php

date_default_timezone_set('Asia/Tokyo');

if (isset($_POST["update"]) && !empty($_POST["title"]) && !empty($_POST["text"])) {
    update($_GET["id"], $_POST["title"], $_POST["text"]);
} else if (!empty($_GET["id"])) {
    get($_GET["id"]);
}

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

function update($id, $title, $text)
{
    $sql = "UPDATE articles SET title = ? , body = ? WHERE id = $id";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindValue(1, $title);
    $stmt->bindValue(2, $text);
    $stmt->execute();
    // header('Location: http://localhost:8888/php_project/miniblog/article_list.php');
    header('Location: http://localhost:8888/php_project/miniblog/?id=$id');
}

function get($id)
{
    $sql = "SELECT * FROM articles WHERE id = ?";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindValue(1, $id);
    $stmt->execute();
    return $stmt->fetchAll();
}

$view = get($_GET["id"]);


// echo $_SERVER['REQUEST_URI'] . PHP_EOL;
// echo $_SERVER['HTTP_HOST'] . PHP_EOL;
// echo (empty($_SERVER['HTTPS']) ? "http//" : "https://");
// echo "<br>";
// echo (empty($_SERVER['HTTPS']) ? "http//" : "https://") . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新ページ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4040ff;
            padding: 16px;
        }

        h1 {
            color: #fff;
            font-size: 24px;
        }

        .strong {
            font-weight: bold;
        }

        .container {
            padding: 16px;
        }

        .input_area,
        .input_textarea {
            width: 80%;
            margin-left: 32px;
            display: block;
            padding: 6px;
            margin-bottom: 16px;
        }

        .input_textarea {
            height: 180px;
        }
    </style>
</head>

<body>
    <?php foreach ($view as $data) {
    }
    ?>
    <header>
        <h1>ミニブログ</h1>
    </header>
    <div class="container">
        <form method="post">
            <p class="strong">タイトル</p>
            <input type="text" name="title" class="input_area" value="<?php echo  $data["title"]; ?>" />
            <p class="strong">本文</p>
            <textarea name="text" class="input_textarea"><?php echo $data["body"]; ?></textarea>
            <button type="submit" name="update">更新する</button>
        </form>
    </div>
</body>

</html>
