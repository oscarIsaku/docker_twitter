<?php
session_start();

//DB情報を読み込み
require('database.php');

if (isset($_SESSION["id"])) {
  $id = $_SESSION["id"];

  // 投稿を検査する
  $messages = $dbh->prepare('SELECT * FROM posts WHERE id = ?');
  $messages->execute(array($id));
  $message = $messages->fetch();

  if ($message["member_id"] == $_SESSION["id"]) {
    // 削除する
    $del = $dbh->prepare('DELETE FROM posts WHERE id = ?');
    $del->execute(array($id));
  }
}

header('Location: index.php');
exit();

?>