<?php
session_start();

//DB情報を読み込み
require('database.php');

if (empty($_REQUEST["id"])) {
  header('Location: index.php');
  exit();
}

// 投稿を取得する
$posts = $dbh->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id = p.member_id AND p.id = ? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST["id"]));

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="id=edge">
  <title>掲示板</title>
</head>
<body>
  <div>
    <div>
      <h1>掲示板</h1>
    </div>
    <div>
      <p>&laquo;<a href="index.php">一覧に戻る</a></p>
      <?php if ($post = $posts->fetch()): ?>
      <div>
        <img src="member_picture/<?php echo htmlspecialchars($post["picture"], ENT_QUOTES); ?>"width="48" height="48" alt="<?php echo htmlspecialchars($post["name"], ENT_QUOTES); ?>">
        <p><?php echo htmlspecialchars($post["message"], ENT_QUOTES); ?><span> (<?php echo htmlspecialchars($post["name"], ENT_QUOTES); ?>) </span></p>
        <p><?php echo htmlspecialchars($post["created"], ENT_QUOTES); ?></p>
      </div>
      <?php else: ?>
        <p>その投稿は削除されたか、URLが間違えてます</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>