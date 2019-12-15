<?php

//PDO接続情報
$dsn = 'mysql:dbname=database;host=db';
$user = 'root';
$password = 'root';

try {
  $dbh = new PDO($dsn, $user, $password);
} catch(PDOException $e) {
  echo "接続失敗: " . $e->getMessage() . "\n";
  exit();
}
?>