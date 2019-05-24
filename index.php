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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <title>タイトル</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        .head {
            margin-bottom: 15px;
        }
        #s_img {
            margin-left: 120px;
        }
        .rest {
            border: 2px solid black;
            border-radius: 10px;
            padding-left: 10px;
            margin-bottom: 10px;
        }
        .rest-name {
            font-size: 23px;
            padding-top: 10px;
        }
        .rest-img {
            padding-bottom: 65px;
        }
        .rest-address {
            padding-bottom: 100px;
        }
        .paging {
            padding-top: 30px;
            padding-bottom: 40px;
        }
        #footer {
            float: right;
            padding-top: 50px;
            padding-bottom: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- head -->
        <div class="head py-3">
            <div class="head-logo">
                <a href="index.php"><img src="images/logo1.PNG"></a>
            </div>
            <div class="login text-right py-3">
                <?php if((isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time())): ?>
                <p><a href="profile.php"><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?></a>さん、こんにちは！</p>
                <?php else: ?>
                <a href="join/index.php"><p>ログイン・新規登録</p></a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- container -->
        <div class="contents">
            <!-- 検索 -->
            
            <div id="search" class="row">
                <form id="form1" class="form-group form-inline" action="" method="get">
                <div>
                    <img id="s_img" src="images/search.PNG" width="50px" height="auto">
                </div>
                <div>
                    <input name="search_word" class="search_word form-control form-control-lg" id="search_word" size="120" type="text" placeholder="キーワードを入力" value="<?php if(isset($_GET['search_word'])) echo $_GET['search_word']; ?>">
                </div>
                <div>
                    <input class="btn btn-primary btn-lg" id="s_btn" type="submit" value="検索する" >
                </div>
                </form>
            </div>
			
            <!-- お店情報 -->
            <div id="contents" class="col-9 float-right">
                <div class="contents-item">
                    <dl>
                        <div class="contents-rest">
                            <?php if(!isset($rest_json->rest)): ?>
                                検索結果がありません。
                            <?php else: ?>
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
                                                <img src="<?php if($img->shop_image1){
																	print($img->shop_image1);
																}else {
																	print($img->shop_image2);
																}
																?>" align="left"><br>
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
                            <?php endif; ?>
                        </div>
                        <ul class="paging">
                            <?php if($page >= 2): ?>
                            <a href="<?php echo '?page=' . ($page-1); ?>"><?php print($page-1); ?>ページへ</a>
                            <?php endif; ?>
                            ｜
                            <?php if($page < $max_page): ?>
                            <a href="<?php echo '?page=' . ($page+1); ?>"><?php print($page+1); ?>ページへ</a>
                            <?php endif; ?>
                        </ul>
                    </dl>
                </div>
                <div id="footer">
                    <a href="contact.php">お問い合わせ</a>
					<a href="https://api.gnavi.co.jp/api/scope/" target="_blank">
						<img src="https://api.gnavi.co.jp/api/img/credit/api_180_60.gif" width="180" height="60" border="0" alt="グルメ情報検索サイト　ぐるなび">
					</a>
                </div>
            </div>
            <!-- 絞り込み -->
            <div id="sidebar" class="col-3 float-left">
                <form id="form2" action="" method="get">
                    <input type="hidden" name="search_word" value="<?php if(isset($GET['search_word'])) echo $_GET['search_word']; ?>">
                    <select name="pref" id="pref">
                        <option value="">都道府県</option>
                        <?php foreach($json->pref as $pref): ?>
                            <option name="pref" value="<?php print($pref->pref_code); ?>" <?php if(isset($_GET['pref']) && $pref->pref_code == $_GET['pref']) echo "selected"; ?> ><?php print($pref->pref_name); ?></option>
                        <?php endforeach; ?>
                        <input id="r_btn" class="btn btn-primary" type="submit" value="絞り込む">
                    </select>
                </form>
            </div>
        </div>
    </div>
	
</body>
</html>