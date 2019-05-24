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
        $message = $db->prepare('INSERT INTO posts SET rest_id=?, member_id=?, review=?, created_at=NOW()');
        $message->execute(array(
            $_GET['id'],
            $member['id'],
            $_POST['review']
        ));
        //var_dump($message->debugDumpParams());
        //exit;
        
        header('Location: details.php?id='.$_GET['id']); 
        exit();
    }
}

//投稿を取得する
$posts = $db->query("SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.rest_id='".$_GET['id']."'ORDER BY p.created_at DESC");

//詳細ページお店の名前
$rest_name = 'http://api.gnavi.co.jp/RestSearchAPI/20150630/?format=json&keyid=4cfc2a2d9b143298d765078066e01c3e&id='.$_GET['id'];
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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="style1.css">
        <style>
            .rev {
                padding-top: 140px;
            }
            .rev-dis {
                background-color: #e0ffff;
            }
            .rev-st {
                font-weight: bold;
            }
            .name {
                font-weight: normal;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <div class="head py-3">
                <div class="head-logo">
                    <a href="index.php"><img src="images/logo1.PNG"></a>
                </div>
                <div class="login text-right pt-3">
                    <?php if((isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time())): ?>
                    <a href="profile.php"><p><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、こんにちは！</p>
                    <?php else: ?>
                    <a href="join/index.php"><p>ログイン・新規登録</p></a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div id="contents py-3">
                <a href="index.php">戻る</a>
                <div class="details-rest">
                    <div class="details">
                        <div class="name h3">
                            <?php print($name_json->rest->name); ?>
                        </div>
                        <div class="img">
                            <img src="<?php print($name_json->rest->image_url->shop_image1); ?>" />
                        </div>    
                            <div class="info pt-4 pb-5">
                                    電話番号：<?php print($name_json->rest->tel); ?><br>
                                    住所：<?php print($name_json->rest->address); ?><br>
                                    営業時間：<?php print($name_json->rest->opentime); ?><br>
                                    休業日：<?php print($name_json->rest->holiday); ?>
                            </div>
                    </div>
                    <div class="review">
                        <div class="rev h3 pb-1">
                            <p>レビュー</p>
                        </div>
                        <div class="rev-dis">
                            <?php foreach($posts as $post): ?>
                                <div class="msg">
                                    <img src="join/member_pictures/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" width="48"height="48" alt="<?php echo htmlspecialchars($post['name'],ENT_QUOTES); ?>" />
                                    <p class="rev-st"><?php echo htmlspecialchars($post['review'], ENT_QUOTES); ?><span class="name"> (<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>) </span></p>
                                    <p class="day"><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="rev-input pb-4">
                            <form action="" method="post">
                                <textarea class="form-control" name="review" cols="50" rows="5"></textarea>
                                <input class="btn btn-primary" type="submit" value="送信" />
                            </form>
                        </div>
                    </div>
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