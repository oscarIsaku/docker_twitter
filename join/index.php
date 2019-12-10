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
    <dd><input type="text" name="name" size="35" maxlength="255"></dd>
    <dt>メールアドレス <span style="color:red">必須</span></dt>
    <dd><input type="text" name="email" size="35" maxlength="255"></dd>
    <dt>パスワード <span style="color:red">必須</span></dt>
    <dd><input type="password" name="password" size="10" maxlength="20"></dd>
    <dt>写真など</dt>
    <dd><input type="file" name="image" size="35"></dd>
  </dl>
  <div><input type="submit" value="入力内容を確認する"></div>
</form>
</body>
</html>