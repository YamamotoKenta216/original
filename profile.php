<?php
session_start();
require('dbconnect.php');

//ログインのチェック
if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();
    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    //ログインしていない
    header('Location: login.php');
    exit();
}

//取得する
$posts = $db->query('SELECT m.name, m.picture, p. * FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

//登録内容変更

if (!empty($_POST)) {
    //プロフィール更新処理
    
    if (!empty($_POST['name'])) {
        $statement = $db->prepare('UPDATE members SET name=? WHERE id=?');
        $ret = $statement->execute(array(
            $_POST['name'],
            $member['id']
        ));
        $member['name'] = $_POST['name'];
    }
    if (!empty($_POST['address'])) {
        $statement = $db->prepare('UPDATE members SET email=? WHERE id=?');
        $ret = $statement->execute(array(
            $_POST['address'],
            $member['id']
        ));
        $member['email'] = $_POST['address'];
    }
    if (!empty($_FILES['image'])) {
        $image = date('YmdHis') . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'join/member_pictures/' . $image);
 
        $statement = $db->prepare('UPDATE members SET picture=? WHERE id=?');
        $ret = $statement->execute(array(
            $image,
            $member['id']
        ));
        $member['picture'] = $image;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイトル</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <!-- head -->
        <div class="head pt-3">
            <div class="head-logo">
                <a href="index.php"><img src="images/logo1.PNG"></a>
            </div>
            <div class="login text-right py-3">
                <?php if((isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time())): ?>
                <p><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さん、こんにちは！</p>
                <?php else: ?>
                <a href="join/index.php"><p>ログイン・新規登録</p></a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- contents -->
        <div class="contents py-3">
            <div class="text-right">
                <a href="logout.php">ログアウト</a>
            </div>
            <h1>プロフィール</h1>
            <div class="form center-block">
                <?php if(isset($_POST)): ?>
                    <div class="form-group">
                        <form action="" method="post" enctype="multipart/form-data">
                            <dl>
                                <dt>お名前</dt>
                                    <dd><input class="form-control" type="text" name="name" size="35" maxlength="255" placeholder="<?php if(isset($member['name'])) echo htmlspecialchars($member['name'], ENT_QUOTES); ?>" value="<?php if(isset($_POST['name'])) echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" /></dd>
                                <dt>メールアドレス</dt>
                                    <dd><input class="form-control" type="text" name="address" size="35" maxlength="255" placeholder="<?php if(isset($member['email'])) echo htmlspecialchars($member['email'], ENT_QUOTES); ?>" value="<?php if(isset($_POST['address'])) echo htmlspecialchars($_POST['address'], ENT_QUOTES); ?>" /></dd>
                                <dt>プロフィール画像</dt>
                                        <img src="join/member_pictures/<?php if(isset($member['picture'])) echo htmlspecialchars($member['picture'], ENT_QUOTES); ?>" width="48"height="48" alt="登録していません"/>
                                    <dd><input type="file" name="image" size="35"></dd>
                            </dl>
                                <input type="submit" class="btn btn-primary" value="変更する">
                        </form>
                    </div>
                <?php else: ?>
                    変更されました。
                    <a href="index.php">ホームに戻る</a>
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