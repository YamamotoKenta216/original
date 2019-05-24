<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイトル</title>
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
        h1 {
            padding-top: 10px;
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
            <h1>パスワードの再設定</h1>
            <p>新しいパスワードを入力してください</p>
            <div class="form-group row">
                <form action="" method="post" enctype="multipart/form-data">
                    <dl>
                        <dt>パスワード</dt>
                        <dd>
                            <input type="password" class="form-control" name="password" size="10" maxlength="20" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
                        </dd>
                        <dt>パスワード確認用</dt>
                        <dd>
                            <input type="password" class="form-control" name="password" size="10" maxlength="20" value="<?php if(isset($_POST['password'])) echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
                        </dd>
                    </dl>
                <div><input class="btn btn-primary" type="submit" value="変更する"></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>