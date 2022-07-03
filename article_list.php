<?php

date_default_timezone_set('Asia/Tokyo');

if (!empty($_POST["title"]) && !empty($_POST["text"])) {
    create($_POST["title"], $_POST["text"]);
} else if (isset($_POST["delete"])) {
    delete($_POST["id"]);
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

function view()
{
    $sql = "SELECT * FROM articles ORDER BY created_at DESC";
    $stmt = dbConnect()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function create($title, $body)
{
    $now = date('Y/m/d H:i:s');
    $sql = "INSERT INTO articles (title,body,created_at,updated_at) VALUES(?,?,?,?)";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $body, PDO::PARAM_STR);
    $stmt->bindValue(3, $now);
    $stmt->bindValue(4, $now);
    $stmt->execute();
}

function delete($id)
{
    $sql = "DELETE FROM articles WHERE id = ?";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
}

$dataList = view();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事一羅ページ</title>
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.18.1/build/cssreset/cssreset-min.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <h1>ミニブログ</h1>
    </header>
    <div class="container">
        <a href="input.html">記事を書く</a>
        <div class="article_area">
            <?php
            foreach ($dataList as $data) {
            ?>
                <form method="post">
                    <div class="article_container">
                        <input type="hidden" name="id" value="<?php echo $data["id"]; ?>">
                        <h2 class="title">
                            <a href="view.php/?id=<?php echo $data["id"]; ?>">
                                <?php echo $data["title"]; ?>
                            </a>
                        </h2>
                        <div class="article_sub_area">
                            <p class="date"><?php echo $data["created_at"]; ?></p>
                            <button type="submit" name="delete" class="btn">削除</button>
                            <a href="update.php?id=<?php echo $data["id"]; ?>" class="btn">更新</a>
                        </div>
                    </div>
                </form>
                <hr>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>
