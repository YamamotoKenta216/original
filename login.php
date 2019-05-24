<?php
require('dbconnect.php');
session_start();

if(isset($_COOKIE['email'])) {
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';
}

if(!empty($_POST)) {
    //ログインの処理
    if($_POST['email'] != '' && $_POST['password'] != '') {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
    $member = $login->fetch();
    
        if($member) {
            //ログイン成功
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
                //ログイン情報を記録する
                if($_POST['save'] == 'on') {
                    setcookie('email', $_POST['email'], time()+60*60*24*14);
                    setcookie('password', $_POST['password'], time()+60*60*24*14);
                }
            header('Location: index.php'); exit();
        } else {
            $error['login'] = 'failed';
        }
    } else {
        $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale=1.0>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイトル</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style1.css">
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
                <a href="index.php"><img src="images/logo1.PNG" alt="logo"></a>
            </div>
        </div>
    <div class="contents pt-3">
        <h1>ログイン</h1>
        <a href="join/">新規登録はこちら</a>
        <p>メールアドレスとパスワードを入力してください。</p>
        <div class="form-group form-row">
            <form action="" method="post">
                <dl>
                    <dt>メールアドレス</dt>
                    <dd>
                    <input type="text" class="form-control" name="email" size="35" maxlength="255" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
                    <?php if(isset($error['login']) && $error['login'] == 'blank'): ?>
                    <p class="error">メールアドレスとパスワードをご記入ください</p>
                    <?php endif; ?>
                    <?php if(isset($error['login']) && $error['login'] == 'failed'): ?>
                    <p class="error"> ログインに失敗しました。正しくご記入ください。</p>
                    <?php endif; ?>
                    </dd>
                    <dt>パスワード</dt>
                    <dd>
                    <input type="password" class="form-control" name="password" size="35" maxlength="255" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />   
                    </dd>
                    <dd>
                        パスワードを忘れた方は<a href="join/password.php">こちら</a>
                    </dd>
                    <dt>ログイン情報の記録</dt>
                    <dd>
                    <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
                    </dd>
                </dl>
                <div><input type="submit" class="btn btn-primary" value="ログインする"></div>
        </form>
    </div>
 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>