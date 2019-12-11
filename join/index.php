<?php
session_start();
//DB情報を読み込み
require('database.php');

if (!empty($_POST)) {
  if ($_POST['name'] == '') {
    $error['name'] = 'blank';
  }
  if ($_POST['email'] == '') {
    $error['email'] = 'blank';
  }
  if (strlen($_POST['password'] < 4)) {
    $error['password'] = 'length';
  }
  if ($_POST['password'] == '') {
    $error['password'] = 'blank';
  }
  $fileName = $_FILES['image']['name'];
  if (!empty($fileName)) {
    $ext1 = substr($fileName, -3);
    $ext2 = substr($fileName, -4);
    if ($ext1 != 'jpg' && $ext1 != 'gif' && $ext2 != 'jpeg') {
      $error['image'] == 'type';
    }
  }

  // 重複アカウント確認
  if (empty($error)) {
    $member = $dbh->prepare('SELECT count(*) AS cnt FROM members WHERE email = ?');
    $member->execute(array($_POST['email']));
    $record = $member->fetch();
    if ($record['cnt'] > 0) {
      $error['email'] = 'duplicate';
    }
  }

  if (empty($error)) {
    // 画像をアップロードする
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
    $_SESSION['join'] = $_POST;
    $_SESSION['join']['image'] = $image;
    header('Location: check.php');
    exit();
  }
}

// 書き直し
if ($_REQUEST['action'] == 'rewrite') {
  $_POST = $_SESSION['join'];
  $error['rewrite'] == true;
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>会員登録</title>
</head>
<body>
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt>ニックネーム <span style="color:red">必須</span></dt>
    <dd><input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>"></dd>
    <?php if ($error['name'] == 'blank'): ?>
    <p style="color:red">* ニックネームを入力してください</p>
    <?php endif; ?>
    <dt>メールアドレス <span style="color:red">必須</span></dt>
    <dd><input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"></dd>
    <?php if ($error['email'] == 'blank'): ?>
    <p style="color:red">* メールアドレスを入力してください</p>
    <?php endif; ?>
    <?php if ($error['email'] == 'duplicate'): ?>
    <p style="color:red">* 指定されたメールアドレスは既に登録されています</p>
    <?php endif; ?>
    <dt>パスワード <span style="color:red">必須</span></dt>
    <dd><input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>"></dd>
    <?php if ($error['password'] == 'blank'): ?>
    <p style="color:red">* パスワードを入力してください</p>
    <?php endif; ?>
    <?php if ($error['password'] == 'length'): ?>
    <p style="color:red">* パスワードは4文字以上で入力してください</p>
    <?php endif; ?>
    <dt>写真など</dt>
    <dd><input type="file" name="image" size="35"></dd>
    <?php if ($error['image'] == 'type'): ?>
    <p style="color:red">* 写真などは「.gif」または「.jpg」,「.jpeg」の画像を指定してください</p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
    <p style="color:red">* 恐れ入りますが、再度画像を指定してください</p>
    <?php endif; ?>
  </dl>
  <div><input type="submit" value="入力内容を確認する"></div>
</form>
</body>
</html>