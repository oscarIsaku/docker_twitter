<?php
session_start();

//DB情報を読み込み
require('database.php');

//関数の読み込み
require('function.php');

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
    if (empty($_POST["reply_post_id"])) {
      $_POST["reply_post_id"] = 0;
    }
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
      <dt><?php echo h($member["name"]); ?>さん、メッセージをどうぞ</dt>
      <dd>
        <textarea name="message" cols="50" rows="5">
          <?php echo h($message); ?>
        </textarea>
        <input type="hidden" name="reply_post_id" value="<?php echo h($_REQUEST["res"]); ?>">
      </dd>
    </dl>
    <div>
      <input type="submit" value="投稿する">
    </div>
  </form>
  <?php foreach($posts as $post): ?>
  <div>
    <img src="member_picture/<?php echo h($post["picture"]); ?>" width="48" height="48" alt="<?php echo h($post["name"]); ?>">
    <p><?php echo makeLink(h($post["message"])); ?><span> (<?php echo h($post["name"]); ?>) </span>[<a href="index.php?res=<?php echo h($post["id"]); ?>">Re</a>]</p>
    <p>
      <a href="view.php?id=<?php echo h($post["id"]); ?>"><?php echo h($post["created"]); ?></a>
      <?php if ($post["reply_post_id"] > 0): ?>
      <a href="view.php?id=<?php echo h($post["reply_post_id"]); ?>">返信元のメッセージ</a>
      <?php endif;?>
    </p>
  </div>
<?php endforeach; ?>
</body>
</html>