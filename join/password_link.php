<?php
    require('../dbconnect.php');
    // ユーザーの存在を確認
    $statement = $db->prepare('SELECT * FROM members WHERE reset_pass=?');
    $statement->execute(array(
            $_GET['pass']
        ));
    $member = $statement->fetch();
    if(!$member && $_GET['pass'] == 0){
        // ユーザー存在しない
        exit('不正な処理です');
    }
    if (!empty($_POST)) {
        // パスワードの更新処理
        $statement = $db->prepare('UPDATE members SET password=?, reset_pass=? WHERE id=?');
        $ret = $statement->execute(array(
                sha1($_POST['password']),
                0,
                $member['id']
            ));
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
            <?php if(!isset($_POST['password'])):?>
            <div class="form-group form-row">
                <form action="" method="post" enctype="multipart/form-data">
                    <h1>パスワード再設定</h1>
                    <dl>
                        <dt>新しいパスワードを入力してください</dt>
                        <dd>
                            <div class="col">
                                <input type="password" class="form-control" name="password" size="35" >
                            </div>
                        </dd>
                    </dl>
                    <div>
                        <input type="submit" class="btn btn-primary" value="送信する">
                    </div>
                </form>
                <?php else: ?>
                    <p>パスワードを変更しました。<p></p>
                    <a href="../login.php">ログイン</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>