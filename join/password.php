<?php
require('../dbconnect.php');
mb_language('japanese');
mb_internal_encoding('UTF-8');
if(!empty($_POST)){
    // メールアドレスからユーザーを取得する
    $login = $db->prepare('SELECT * FROM members WHERE email=?');
    $login->execute(array(
            $_POST['email'],
        ));
    $member = $login->fetch();
    if(!$member){
        // ユーザー存在しない
        $error = true;        
    } else {
        // ユーザーが存在する
        // そのユーザー特有のパスを発行する
        $pass = substr(base_convert(md5(uniqid()), 16, 36), 0, 10);
        
        // そのパスをDBに保存
        $statement = $db->prepare('UPDATE members SET reset_pass=? WHERE id=?');
        $ret = $statement->execute(array(
                    $pass,
                    $member['id']
                ));
        
        $to = $_POST['email'];
        $title = 'タイトルです。';
        $body = 'パスワード変更は<a href="https://yamamoto-kenta-1.paiza-user.cloud/~ubuntu/original/join/password_link.php?pass='.$pass.'">こちら</a>';
        $success = mb_send_mail($email, $title, $body);
    }
    
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイトル</title>
    <!-- Bootstrap CSS -->
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
        .error {
            color: red;
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
            <h1>パスワードを忘れた場合</h1>
            <a href="../login.php">ログインする</a>
            <div class="form-group form-row">
                <form action="" method="post" enctype="multipart/form-data">
                    <dl>
                        <dt>メールアドレス</dt>
                        <dd>
                            <input type="text" class="form-control" name="email" size="35" maxlength="255" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
                        </dd>
                        <?php if(isset($error)): ?>
                            <p class="error"> メールアドレスが存在しません。正しくご記入ください。</p>
                        <?php endif; ?>
                    </dl>
                    <div><input type="submit" class="btn btn-primary" value="送信する"></div>
                </form>
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