<?php
session_start();
//DB情報を読み込み
require('../database.php');

//関数の読み込み
require('../function.php');

if (!isset($_SESSION['join'])) {
  header('Location: index.php');
  exit();
}

if (!empty($_POST)) {
  // 登録処理をする
  $statement = $dbh->prepare('INSERT INTO members SET name = ?, email = ?, password = ?, picture = ?, created = NOW()');
  echo $ret = $statement->execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    sha1($_SESSION['join']['password']),
    $_SESSION['join']['image']
  ));
  unset($_SESSION['join']);

  header('Location: thanks.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>会員登録</title>
</head>
<body>
<form action="" method="post">
  <input type="hidden" name="action" value="submit" />
  <dl>
    <dt>ニックネーム</dt>
    <dd>
      <?php echo h($_SESSION['join']['name']); ?>
    </dd>
    <dt>メールアドレス</dt>
    <dd>
    <?php echo h($_SESSION['join']['email']); ?>
    </dd>
    <dt>パスワード</dt>
    <dd>【表示されません】</dd>
    <dt>写真など</dt>
    <dd>
      <img src="../member_picture/<?php echo h($_SESSION['join']['image']); ?>" width="100" height="100" alt="" />
    </dd>
  </dl>
  <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する"></div>
</form>
</body>
</html>