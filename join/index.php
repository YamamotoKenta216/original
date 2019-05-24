<?php
require('../dbconnect.php');

session_start();

if (!empty($_POST)) {
    //エラー項目の確認
    //名前のエラー
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    //メールアドレスのエラー
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    //パスワードのエラー
    if (strlen($_POST['password']) < 6) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }
    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
            $error['image'] = 'type';
        }
    }
    //重複アカウントチェック
    if (empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if($record['cnt']) {
            $error['email'] = 'duplicate';
        }
    }
    
    if (empty($error)) {
        //画像をアップロードする
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'member_pictures/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location: check.php');
        exit();
    }
}

//書き直し
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite'] = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイトル</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
                <a href="index.php"><img src="../images/logo1.PNG"></a>
            </div>
        </div>

        <div class="contents pt-3">
            <h1>新規登録</h1>
            <a href="../login.php">ログインする</a>
            <p>次のフォームに必要事項をご記入ください。</p>
            <div class="form-group form-row">
                <form action="" method="post" enctype="multipart/form-data">
                    <dl>
                        <dt>ニックネーム</dt>
                        <dd>
                            <input type="text" class="form-control" name="name" size="35" maxlength="255" value="<?php if(isset($_POST['name'])) echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
                            <?php if(isset($error['name']) && $error['name'] == 'blank'): ?>
                            <p class="error">ニックネームを入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dt>メールアドレス</dt>
                        <dd>
                            <input type="text" class="form-control" name="email" size="35" maxlength="255" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
                            <?php if(isset($error['email']) && $error['email'] == 'blank'): ?>
                            <p class="error">メールアドレスを入力してください</p>
                            <?php endif; ?>
                            <?php if (isset($error['email']) && $error['email'] == 'duplicate'): ?>
                            <p class="error">指定されたメールアドレスはすでに登録されています</p>
                            <?php endif; ?>
                        </dd>
                        <dt>パスワード</dt>
                        <dd>
                            <input type="password" class="form-control" name="password" size="10" maxlength="20" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
                            <?php if(isset($error['password']) && $error['password'] == 'blank'): ?>
                            <p class="error">パスワードを入力してください</p>
                            <?php endif; ?>
                            <?php if(isset($error['password']) && $error['password'] == 'length'): ?>
                            <p class="error">パスワードは7文字以上で入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dt>写真を追加する</dt>
                        <dd><input type="file" name="image" size="35"></dd>
                            <?php if(isset($error['image']) && $error['image'] == 'type'): ?>
                            <p class="error">写真などは「.gif」または「.jpg」の画像を指定してください</p>
                            <?php endif; ?>
                            <?php if (!empty($error)): ?>
                            <p class="error">画像を改めて指定してください</p>
                            <?php endif; ?>
                    </dl>
                    <div><input class="btn btn-primary" type="submit" value="確認する"></div>
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