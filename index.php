<?php
session_start();
require('dbconnect.php');
require('api.php');

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
        <!-- head -->
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
        
        <!-- container -->
        <div id="container">
            <!-- 検索 -->
            <div id="search">
                <form id="form1" action="" method="get">
                    <img class="s_img" src="images/search.PNG">
                    <input name="search_word" id="search_word" type="text" placeholder="キーワードを入力" value="<?php if(isset($_GET['search_word'])) echo $_GET['search_word']; ?>">
                    <input id="s_btn" type="submit" value="検索する">
                </form> 
            </div>
            <!-- お店情報 -->
            <div id="contents">
                <div class="contents-item">
                    <dl>
                        <div class="contents-rest">
                            <?php foreach($rest_json->rest as $rest): ?>
                            <div class="rest">
                                <!-- お店の名前 -->
                                <div class="rest-name">
                                    <dt>
                                        <a href="details.php?id=<?php print($rest->id); ?>"><?php print($rest->name); ?><br></a>
                                    </dt>
                                </div>
                                <!-- お店の写真 -->
                                <div class="rest-img">
                                    <dd>
                                        <?php $img = $rest->image_url; ?>
                                        <a href="details.php?id=<?php print($rest->id); ?>">
                                            <img src="<?php print($img->shop_image1); ?>" align="left"><br>
                                        </a>
                                    </dd>
                                </div>
                                <div class="rest-address">
                                    <dd>
                                        <p>住所</p>
                                        <?php print($rest->address); ?><br>
                                    </dd>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <ul class="paging">
                            <?php if($page >= 2): ?>
                            <a href="<?php echo '?page=' . ($page-1); ?>"><?php print($page-1); ?>ページへ</a>
                            <?php endif; ?>
                            ｜
                            <a href="<?php echo '?page=' . ($page+1); ?>"><?php print($page+1); ?>ページへ</a>
                        </ul>
                    </dl>
                </div>
            </div>
            <!-- 絞り込み -->
            <div id="sidebar">
                <form id="form2" action="" method="get">
                    <input type="hidden" name="search_word" value="<?php echo $_GET['search_word']; ?>">
                    <select name="pref" id="pref">
                        <option value="">都道府県</option>
                        <?php foreach($json->pref as $pref): ?>
                            <option name="pref" value="<?php print($pref->pref_code); ?>" <?php if(isset($_GET['pref']) && $pref->pref_code == $_GET['pref']) echo "selected"; ?> ><?php print($pref->pref_name); ?></option>
                        <?php endforeach; ?>
                        <input id="r_btn" type="submit" value="絞り込む">
                    </select>
                </form>
            </div>
        </div>
    </div>
</body>
</html>