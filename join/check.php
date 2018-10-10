<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST)) {
    //登録処理する
    $statemant = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created_at=NOW()');
    echo $ret = $statemant->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);
    
    //デバッグコード
    //echo $statemant->debugDumpParams();
    //exit();
    
    header('Location: thanks.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイトル</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="../images/logo.PNG" alt="logo"></a>
        </div>
    </header>
    
    <div id="wrap">
        <div class="content">
            <h1>新規登録</h1>
        <form action="" method="post">
            <input type="hidden" name="action" value="submit" />
            <dl>
                <dt>ニックネーム</dt>
                <dd>
                <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
                </dd>
                <dt>メールアドレス</dt>
                <dd>
                <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?>
                </dd>
                <dt>パスワード</dt>
                <dd>【表示されません】</dd>
                <dt>写真を追加する</dt>
                <dd>
                <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>" width="100" height="100" alt="" />
                </dd>
            </dl>
            <div><a href="index.php?action=rewrite">戻る</a></div>
            <div><input type="submit" value="登録する"></div>
        </form>
        </div>
    </div>
</body>
</html>