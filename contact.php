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
        <div class="head py-3">
            <div class="head-logo">
                <a href="index.php"><img src="images/logo1.PNG"></a>
            </div>
            <div class="login text-right pt-3">
                <?php if((isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time())): ?>
                <p><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さん、こんにちは！</p>
                <?php else: ?>
                <a href="join/index.php"><p>ログイン・新規登録</p></a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- contents -->
        <div class="contents py-3">
            <h1>お問い合わせ</h1>
            <div class="form form-group center-block">
                <form action="" method="post" enctype="multipart/form-data">
                    <dl>
                        <dt>お名前</dt>
                        <dd><input type="text" class="form-control" name="name" size="35" maxlength="255" value="<?php if(isset($_POST['name'])) echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" /></dd>
                        <dt>お問い合わせ内容</dt>
                        <dd><textarea type="text" class="form-control" name="content" rows="15"></textarea></dd>
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