<?php

header('Content-Type: text/html; charset=UTF-8');
// データベースへの接続--------------------------------------
$dsn = '?データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

// テーブル消去（初期化）-------------------------
if(!empty($_POST['alldelete'])){
$sql = "DROP TABLE data";
$pdo->query($sql);
}

//テーブル作成----------------------------------
$sql= "CREATE TABLE data"
. " ("
.  "id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
.  "name char(8),"
.  "comment TEXT,"
.  "time TIMESTAMP,"  
.  "password char(8)"
.  ");";
$pdo->query($sql);

//データの代入----------------------------------------------


if(!empty($_POST["name"]) && !empty($_POST["com"]) && !empty($_POST["pass"]) && empty($_POST["edit2"])){
	$name = $_POST["name"];
	$comment = $_POST["com"];
	$time = date("Y-m-d/ H:i:s");
	$pass = $_POST["pass"];

	$sql = "INSERT INTO data(id,name,comment,time,password) VALUES (0,'$name','$comment','$time','$pass')";
	$pdo->query($sql);

}elseif(empty($_POST["name"]) && !empty($_POST["com"]) && !empty($_POST["pass"]) && empty($_POST["edit2"])){
	echo "名前を入力してください。";

}elseif(!empty($_POST["name"]) && empty($_POST["com"]) && !empty($_POST["pass"]) && empty($_POST["edit2"])){
	echo "コメントを入力してください。";

}elseif(!empty($_POST["name"]) && !empty($_POST["com"]) && empty($_POST["pass"]) && empty($_POST["edit2"])){
	echo "パスワードを設定してください。";

// 編集2 書き込み--------------------------------------------

} elseif(!empty($_POST['name']) && !empty($_POST['com']) && !empty($_POST['edit2']) && empty($_POST["pass"])){
	$edit3 = $_POST['edit2'];
	$NAME  = $_POST["name"];
	$COM   = $_POST["com"];
	$time = date("Y-m-d/   H:i:s");
	
	$editdb ="update data set name='$NAME' , comment='$COM' , time='$time' where id ="
	. "$edit3"
	. ";";
	$pdo -> query($editdb);
}


// 削除---------------------------------------------------------
if(ctype_digit ($_POST['delete']) && !empty($_POST['pass'])){
	$post_delete = $_POST['delete'];
	$post_pass = $_POST['pass'];

	$select ="SELECT id,password FROM data where id ="
	. "$post_delete"
	. ";";
	$stmt = $pdo -> query($select);
	$idpass = $stmt -> fetch();  // DB内のidとpasswordを配列として取得

	if("$post_pass" == $idpass['password']){
		$del = "DELETE FROM data WHERE id ="
		. "$post_delete"
		. ";";
		$pdo -> query($del);
	}else{
		echo "パスワードが違います。";
	}

}elseif(ctype_digit ($_POST['delete']) && empty($_POST['pass'])){
	echo "パスワードを入力してください。";
}



// 編集1 送信フォームへ文字を送るだけ----------------------------------
if(ctype_digit ($_POST['edit']) && !empty($_POST['pass'])){
	$post_edit = $_POST['edit'];
	$post_pass = $_POST['pass'];
	//指定された番号の行のIDと名前、コメント、パスを取得
	$select ="SELECT id,name,comment,password FROM data where id ="  
	. "$post_edit"
	. ";";
	$stmt = $pdo -> query($select);
	$edi = $stmt -> fetch();  // DB内のidとpasswordを配列として取得

	if("$post_pass" == $edi['password']){
		$edit_name = $edi['name'];
		$edit_comment  = $edi['comment'];
		$edit = $_POST['edit'];
	}else{
		echo "パスワードが違います。";
	}
}elseif(ctype_digit ($_POST['edit']) && empty($_POST['pass'])){
	echo "パスワードを入力してください。";
}

?>


<form method = "post" action ="mission_4.php">
<input type="text" name="name" placeholder="名前" value= <?php echo $edit_name ?> > <br>
<input type="text" name="com" placeholder="コメント" value= <?php echo $edit_comment ?> > <br>
<input type="text" name="pass" placeholder="パスワード" > <br>
<input type="hidden" name="edit2" placeholder="通常モード" value= <?php echo $edit ?> > <br>
<input type="submit" value="送信"><br>
</form>

<form method = "post" action ="mission_4.php">
<input type="text" name="delete" placeholder="削除対象番号"> <br>
<input type="text" name="pass" placeholder="パスワード" > <br>
<input type="submit" value="削除"><br>
</form>

<form method = "post" action ="mission_4.php">
<input type="text" name="edit" placeholder="編集対象番号"> <br>
<input type="text" name="pass" placeholder="パスワード" > <br>
<input type="submit" value="編集">
</form>

<form method = "post" action ="mission_4.php">
<input type="hidden" name="alldelete" value="全消去"> <br>
<input type="submit" name=alldelete value="全消去">
</form>


<?php
//テーブルの中身確認用-------------------------------------
$sort = "SELECT * FROM data ORDER BY id ASC;";
$results = $pdo -> query($sort);  //id順に整列する

foreach ($results as $row){
	echo $row['id'].     ',';
	echo $row['name'].   ',';
	echo $row['comment'].',';
	echo $row['time'].   '<br>';
}

?>