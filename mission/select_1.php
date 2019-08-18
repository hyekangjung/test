<?php
//1.  DB接続します xxxにDB名を入れます
try {
$pdo = new PDO('mysql:dbname=mission_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('データベースに接続できませんでした。'.$e->getMessage());
}

//２．データ登録SQL作成
//作ったテーブル名を書く場所  xxxにテーブル名を入れます
$stmt = $pdo->prepare("SELECT * FROM db_table WHERE `indate` !='0000-00-00' ORDER BY indate DESC"); //ここに書く SELECT * FROM xxx_table ORDER BY id DESCとかくと、、、
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる $resultの中に「カラム名」が入ってくるのでそれを表示させる例
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $enc_img = base64_encode($result["upfile"]);
		try {
			//ここの@マークはエラーを出さないおまじないみたいなものなので一旦気にしないでください！
			$imginfo = @getimagesize('data:application/octet-stream;base64,' . $enc_img);
		} catch(Exception $e) {
			$imginfo = false;
		}

    // $sql = "SELECT * FROM `kadai_table`";
    $view .= '<div class="result">';
    $view .= "<p>";
    $view .= $result["indate"];
    $view .= "</p>";
    $view .= "<p>";
    if($imginfo){
        $view .= '<img src="data:' . $imginfo['mime'] . ';base64,'.$enc_img.'" width="60%">';
		  }
    $view .= "</p>";
    $view .= "<p>";
    $view .= $result["text"];
    $view .= "</p>";
    $view .= "</div>";
  }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Review</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main" class="body">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">TOP</a>
      <a class="navbar-brand" href="login.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] $view-->
<div>
    <div class="result"><?=$view?></div>
</div>

<!-- Main[End] -->

</body>
</html>
