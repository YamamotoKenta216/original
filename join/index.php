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
        if ($ext != 'jpg' && $ext != 'gif') {
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
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="head-logo">
            <a href="index.php"><img src="../images/logo.PNG" alt="logo"></a>
        </div>
    </header>
    
    <div id="wrap">
        <div class="content">
            <h1>新規登録</h1>
            <a href="../login.php">ログインする</a>
            <p>次のフォームに必要事項をご記入ください。</p>
            <form action="" method="post" enctype="multipart/form-data">
                <dl>
                    <dt>ニックネーム</dt>
                    <dd>
                        <input type="text" name="name" size="35" maxlength="255" value="<?php if(isset($_POST['name'])) echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
                        <?php if(isset($error['name']) && $error['name'] == 'blank'): ?>
                        <p class="error">ニックネームを入力してください</p>
                        <?php endif; ?>
                    </dd>
                    <dt>メールアドレス</dt>
                    <dd>
                        <input type="text" name="email" size="35" maxlength="255" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
                        <?php if(isset($error['email']) && $error['email'] == 'blank'): ?>
                        <p class="error">メールアドレスを入力してください</p>
                        <?php endif; ?>
                        <?php if (isset($error['email']) && $error['email'] == 'duplicate'): ?>
                        <p class="error">指定されたメールアドレスはすでに登録されています</p>
                        <?php endif; ?>
                    </dd>
                    <dt>パスワード</dt>
                    <dd>
                        <input type="password" name="password" size="10" maxlength="20" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
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
                <div><input type="submit" value="確認する"></div>
            </form>
        </div>
    </div>
</body>
</html>