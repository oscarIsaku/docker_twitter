<?php
session_start();

//DB情報を読み込み
require('database.php');

if (isset($_SESSION["id"]) && $_SESSION["time"] + 3600 > time()) {
  // ログインしている
  $_SESSION["time"] = time();

  $members = $dbh->prepare('SELECT * FROM members WHERE id = ?');
  $members->execute(array($_SESSION["id"]));
  $member = $members->fetch();
} else {
  // ログインする
  header('Location: login.php');
  exit();
}

// 投稿を記録する
if (!empty($_POST)) {
  if ($_POST["message"] != "") {
    $message = $dbh->prepare('INSERT INTO posts SET member_id = ?, message = ?, reply_post_id = ?, created = NOW()');
    $message->execute(array(
      $member["id"],
      $_POST["message"],
      $_POST["reply_post_id"]
    ));

    header('Location: index.php');
    exit();
  }
}

// 投稿を取得する
$posts = $dbh->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id = p.member_id ORDER BY p.created DESC');

// 返信の場合
if (isset($_REQUEST["res"])) {
  $response = $dbh->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id = p.member_id AND p.id = ? ORDER BY p.created DESC');
  $response->execute(array($_REQUEST["res"]));

  $table = $response->fetch();
  $message = '@' . $table["name"] . ' ' . $table["message"]; 
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>掲示板</title>
</head>
<body>
  <form action="" method="post">
    <dl>
      <dt><?php echo htmlspecialchars($member["name"], ENT_QUOTES); ?>さん、メッセージをどうぞ</dt>
      <dd>
        <textarea name="message" cols="50" rows="5">
          <?php echo htmlspecialchars($message, ENT_QUOTES); ?>
        </textarea>
        <input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST["res"], ENT_QUOTES); ?>">
      </dd>
    </dl>
    <div>
      <input type="submit" value="投稿する">
    </div>
  </form>
  <?php foreach($posts as $post): ?>
  <div>
    <img src="member_picture/<?php echo htmlspecialchars(); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($post["name"], ENT_QUOTES); ?>">
    <p><?php echo htmlspecialchars($post["message"], ENT_QUOTES); ?><span> (<?php echo htmlspecialchars($post["name"], ENT_QUOTES); ?>) </span>[<a href="index.php?res=<?php echo htmlspecialchars($post["id"], ENT_QUOTES); ?>">Re</a>]</p>
    <p><?php echo htmlspecialchars($post["created"], ENT_QUOTES); ?></p>
  </div>
<?php endforeach; ?>
</body>
</html>