<?php
session_start();
require('dbconnect.php');
require('api.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();
    
    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    //ログインしていない
    header('Location: login.php'); exit();
}

//投稿を記録する
if(!empty($_POST)) {
    if(isset($_POST['review'])) {
        $message = $db->prepare('INSERT INTO posts SET member_id=?, review=?, created_at=NOW()');
        $message->execute(array(
            $member['id'],
            $_POST['review']
        ));
        //var_dump($message->debugDumpParams());
        //exit;
        
        header('Location: details.php'); 
        exit();
    }
}

//詳細ページお店の名前
$rest_name = 'http://api.gnavi.co.jp/RestSearchAPI/20150630/?format=json&keyid=3e2e5cd74363c280296498b7bffeafab&id='.$_GET['id'];
$name_file = file_get_contents($rest_name);
$name_json = json_decode($name_file);
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
        <div id="wrap">
            <div id="head">
                <div class="head-logo">
                    <a href="index.php"><img src="images/logo.PNG"></a>
                </div>
                <div class="login">
                    <?php if((isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time())): ?>
                    <p><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さん、こんにちは！</p>
                    <?php else: ?>
                    <a href="join/index.php"><p>ログイン・新規登録</p></a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div id="container">
                <a href="index.php">戻る</a>
                <div class="contents-rest">
                    <div class="name">
                        <dl>
                            <dt>
                                <?php print($name_json->rest->name); ?>
                            </dt>
                            <dd>
                                <img src="<?php print($name_json->rest->image_url->shop_image1); ?>" />
                            </dd>
                            <dd>
                                電話番号：<?php print($name_json->rest->tel); ?><br>
                                住所：<?php print($name_json->rest->address); ?><br>
                                営業時間：<?php print($name_json->rest->opentime); ?><br>
                                休業日：<?php print($name_json->rest->holiday); ?>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="review">
                    <div class="rev-input">
                        <form action="" method="post">
                            <p>レビュー</p>
                            <textarea name="review" cols="50" rows="5"></textarea>
                            <input type="submit" value="送信" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>