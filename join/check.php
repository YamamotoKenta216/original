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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../style1.css">
    <style>
        .head {
            padding-top: 15px;
            padding-bottom: 80px;
        }
        .contents {
            padding-left: 35%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="head">
            <div class="head-logo">
                <a href="index.php"><img src="../images/logo1.PNG" alt="logo"></a>
            </div>
        </div>
        <div class="contents">
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
                    <img src="member_pictures/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>" width="100" height="100" alt="" />
                    </dd>
                </dl>
                <div><a href="index.php?action=rewrite">戻る</a></div>
                <div><input type="submit" class="btn btn-primary" value="登録する"></div>
            </form>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>