<?php

//1. POSTデータ取得

//まず前のphpからデーターを受け取る（この受け取ったデータをもとにbindValueと結びつけるため）

var_dump($_POST);
$text = $_POST["text"];
$indate = $_POST["indate"];
$upfile = $_FILES["upfile"]['tmp_name']; //画像を上げる際は$_FILESでやらないだめ！

//2. DB接続します xxxにDB名を入力する
//ここから作成したDBに接続をしてデータを登録します xxxxに作成したデータベース名を書きます
try {
  $pdo = new PDO('mysql:dbname=mission_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getMessage());
}


//３．データ登録SQL作成 //ここにカラム名を入力する
//xxx_table(テーブル名)はテーブル名を入力します
// $stmt = $pdo->prepare("INSERT INTO kadai_table(id,name,indate,place,upfile)VALUES(NULL, name, indate, place, upfile)");
// $stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':indate', $indate, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':upfile',file_get_contents($upfile), PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $status = $stmt->execute();

$stmt = $pdo->prepare("INSERT INTO db_table(id, text, indate, upfile)
VALUES(NULL, :a1, :a2, :a3)");
$stmt->bindValue(':a1', $text, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $indate, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', file_get_contents($upfile), PDO::PARAM_STR);  //画像の場合 file_get_contents($upfile)
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．index.phpへリダイレクト 書くときにLocation: in この:のあとは半角スペースがいるので注意！！
  header("Location: select_1.php");
  exit;
}
?>
