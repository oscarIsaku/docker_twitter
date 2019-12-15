<?php
//DB情報を読み込み
require('database.php');

//関数の読み込み
require('function.php');

session_start();

if ($_COOKIE["email"] != '') {
  $_POST["email"] = $_COOKIE["email"];
  $_POST["password"] = $_COOKIE["password"];
  $_POST["save"] = 'on';
}

if (!empty($_POST)) {
  // ログインの処理
  if ($_POST["email"] != '' && $_POST["password"] != '') {
    $login = $dbh->prepare('SELECT * FROM members WHERE email = ? AND password = ?');
    $login->execute(array(
      $_POST["email"],
      sha1($_POST["password"])
    ));
  }
  $member = $login->fetch();

  if ($member) {
    // ログイン成功
    $_SESSION["id"] = $member["id"];
    $_SESSION["time"] = time();

    if ($_POST['save'] == 'on') {
      setcokkie('email', $_POST["email"], time()+60*60*24*14);
      setcokkie('password', $_POST["password"], time()+60*60*24*14);
    }

    header('Location: index.php');
    exit();
  } else {
    $error["login"] = 'failed';
  }
} else {
  $error["login"] = 'blank';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ログイン</title>
</head>
<body>
  <div>
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
  <p>入会手続きがまだの方はこちらからどうぞ。</p>
  <p>&raquo;<a href="join/">入会手続きをする</a></p>
  </div>
  <form action="" method="post">
    <dl>
      <dt>メールアドレス</dt>
      <dd>
        <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($_POST["email"]); ?>"/>
        <?php if ($error['login'] == 'blank'): ?>
        <p style="color:red">* メールアドレスとパスワードを記入してください</p>
        <?php endif; ?>
        <?php if ($error['login'] == 'failed'): ?>
        <p style="color:red">* ログインに失敗しました。正しくご記入ください。</p>
        <?php endif; ?>
      </dd>
      <dt>パスワード</dt>
      <dd>
        <input type="password" name="password" size="35" maxlength="255" value="<?php echo h($_POST["password"]); ?>"/>
      </dd>
      <dt>ログイン情報の記録</dt>
      <dd>
        <input id="save" type="checkbox" name="save" value="on" /><label for="save">次回からは自動的にログインする</label>
      </dd>
    </dl>
    <div><input type="submit" value="ログインする"></div>
  </form>
</body>
</html>