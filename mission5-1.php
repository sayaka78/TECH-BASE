<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission5-1</title>
<meta charset= "utf-8">
</head>

<body>

 <?php

       //データベースに接続   
       $dsn = 'mysql:dbname=データベース名;host=localhost';
       $user = 'ユーザー名';
       $password = 'パスワード';
       $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


        //4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
        $sql = "CREATE TABLE IF NOT EXISTS tech_text1"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date char(32),"
        . "pass char(30)"
        .");";
        $stmt = $pdo->query($sql);


    
      //編集選択機能
if(!empty($_POST["editNo"]) && !empty($_POST["editpass"])){
    $editNo = $_POST["editNo"];
    $editpass = $_POST["editpass"];
    $sql = 'SELECT * FROM tech_text1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
        
    foreach ($results as $row){
        if($row['id'] == $editNo){
        $ename = $row['name'];
        $ecomment = $row['comment'];
        $epass = $row['pass'];
        $editnumber = $row['id'];     
        }
    }
}

?>  



<form method = "POST" action = keiziban.php>
    <!--投稿フォーム-->
<input type = "text"  name = "name"  value = "<?php if(!empty($epass)){echo $ename;}?>"  placeholder = "<?php if(empty($epass)){echo "名前";}?>"><br>
<input type = "text"  name = "comment"  value = "<?php if(!empty($epass)){echo $ecomment;}?>"  placeholder = "<?php if(empty($epass)){echo "コメント";}?>"><br>		
<input id="pass" type = "text" name = "pass" value ="<?php if(!empty($epass)){echo $epass;}?>"  placeholder = "<?php if(empty($epass)){echo "パスワード";}?>">
<input type = "hidden"  name = "edit_number" value = "<?php if(!empty($epass)){echo $editnumber;}?>" >
<input type = "submit"  name = "btn"value = "送信"><br>
	<!--削除フォーム-->
<input type = "text" name = "deleteNo"  placeholder = "削除対象番号" placeholder="削除番号を入力してください"><br>
<input id="pass" type = "text" name = "delpass"  placeholder = "パスワード">
<input type = "submit"  name = "delete" value = "削除"><br>
	<!--編集フォーム-->
<input type = "text" name = "editNo" placeholder = "編集対象番号" placeholder="編集番号を入力してください"><br>
<input id="pass" type = "text" name = "editpass"  placeholder = "パスワード">
<input type =  "submit" name = "edit" value = "編集"><br> 




<?php
//投稿のパスワード→ s_password pass
//編集のパスワード→ editpass
//削除のパスワード→ delpass
//pass

      //編集実行機能
if(!empty(!empty($_POST["edit_number"]))){
    $id = $_POST["edit_number"];      //ここで編集対象番号の値の受け取りを行う
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $editpass = $_POST["editpass"]; 
    $date = date("Y年m月d日 H時i分s秒");
    $sql = 'update tech_text1 set name=:name,comment=:comment, date=:date,pass=:pass where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $editpass, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
}


        //新規投稿
if(!empty($_POST["name"]) && !empty($_POST["comment"]) and !empty($_POST["pass"]) and empty($_POST["edit_number"])){
    $sql = $pdo -> prepare("INSERT INTO tech_text1 (name, comment,pass,date) VALUES(:name, :comment, :pass,:date)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);        
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql -> bindParam(':date',$date);
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
    $date = date("Y年m月d日 H時i分s秒");
    $sql -> execute();              //実行する
}



         // 削除機能
 if(!empty($_POST['deleteNo']) && !empty($_POST['delpass'])){
    $delete=$_POST['deleteNo']; 
    $delpassword=$_POST['delpass'] ;
    $sql='SELECT*FROM tech_text1';
    $stmt=$pdo->query($sql);
    //$stmt->bindParam(':id',$delete,POD::PARAM_INT);
    //$stmt->execute();
    $results=$stmt->fetchALL();
    foreach($results as $row){
    if($delpassword==$row["pass"]){
        //$id=$delete;
    $sql = 'delete from tech_text1 where delete=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $delete, PDO::PARAM_INT);
	$stmt->execute();
    }
  }
}
  


          //表示機能

    $sql = 'SELECT * FROM tech_text1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row){
        //配列の中で使うのはテーブルのカラム名の物
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].',';
        echo "<br>";
    }


?>