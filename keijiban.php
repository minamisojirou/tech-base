<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
   
    
    <?php
	// DB接続設定
	   $dsn = 'データベース名';
	   $user = 'ユーザー名';
	   $password = 'パスワード';
	   $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //入力フォームのデータを受け取る
       $name = $_POST["name"];
       $comment = $_POST["comment"];
       $delete = $_POST["delete"];
       $edit = $_POST["editno"];
       $hidden = $_POST["hidden"];
       $pass = $_POST["pass"];
       $delpass = $_POST["delpass"];
       $editpass = $_POST["editpass"];
    //テーブル作成
       $sql = "CREATE TABLE IF NOT EXISTS keijiban"
       ."("
       ."id INT AUTO_INCREMENT PRIMARY KEY,"
       ."name char(32),"
       ."comment TEXT,"
       ."password char(11),"
       ."created DATETIME,"
       ."complete char(4)"
       .");";
       $stmt = $pdo -> query($sql);
    
    //時間データの取得
       $DATETIME = new DateTIme();
       $DATETIME = $DATETIME -> format("Y-m-d H:i:s");
       
    //入力項目
       if(!empty($name)and!empty($comment)and!empty($pass)and empty($hidden)){
         $sql = $pdo ->prepare("INSERT INTO keijiban(name,comment,password,created) VALUES(:name,:comment,:password,:created)");
         $sql -> bindParam(":name",$name_sql,PDO::PARAM_STR);
         $sql -> bindParam(":comment",$comment_sql,PDO::PARAM_STR);
         $sql -> bindParam(":password",$password_sql,PDO::PARAM_STR);
         $sql -> bindParam(":created",$DATETIME,PDO::PARAM_STR);
         $name_sql = $name;
         $comment_sql  = $comment;
         $password_sql = $pass;
         $sql -> execute();
         
    //削除項目
       }elseif(!empty($delete)and!empty($delpass)){
         $sql = "SELECT * FROM keijiban";
         $stmt = $pdo -> query($sql);
         $result = $stmt -> fetchAll();
         foreach($result as $row){
            if($row["id"]==$delete and $row["password"]!=$delpass){
              echo "<br>パスワードが違います<br><br>";
            }elseif($row["id"]==$delete and $row["password"]==$delpass){
              $id = $delete;
              $sql = "DELETE FROM keijiban WHERE id=:id";
              $stmt = $pdo -> prepare($sql);
              $stmt -> bindParam(":id",$id,PDO::PARAM_INT);
              $stmt -> execute();
              
              echo "<br>削除しました<br><br>";
            }
         }
       
    //編集項目
        }elseif(!empty($edit)and!empty($editpass)){
         $sql = "SELECT * FROM keijiban";
         $stmt = $pdo -> query($sql);
         $result = $stmt -> fetchAll();
         foreach($result as $row){
          if($row["id"]==$edit and $row["password"]==$editpass){
            $editno = $row["id"];
            $editname = $row["name"];
            $editcomment = $row["comment"];
            echo "<br>編集モード<br><br>";
          }elseif($row["id"]==$edit and $row["password"]!=$editpass){
            echo "<br>パスワードが違います<br><br>";
          }
         }
        }elseif(!empty($hidden) and !empty($name) and !empty($comment) and !empty($pass)){
         $sql = "SELECT * FROM keijiban";
         $stmt = $pdo -> query($sql);
         $result = $stmt -> fetchAll();
         foreach($result as $row){
            if($row["id"]==$hidden and $row["password"]==$pass){
              $id = $hidden;
              $newname = $name;
              $newcomment = $comment;
              $complete = "編集済み";

              $sql = "UPDATE keijiban SET name=:name,comment=:comment,created=:created,complete=:complete WHERE id=:id";
              $stmt = $pdo -> prepare($sql);
              $stmt -> bindParam(":name",$newname,PDO::PARAM_STR);
              $stmt -> bindParam(":comment",$newcomment,PDO::PARAM_STR);
              $stmt -> bindParam(":created",$DATETIME,PDO::PARAM_STR);
              $stmt -> bindParam(":complete",$complete,PDO::PARAM_STR);
              $stmt -> bindParam(":id",$id,PDO::PARAM_INT);
              $stmt -> execute();
              
              echo "<br>編集しました<br><br>";

            }elseif($row["id"]==$hidden and $row["password"]!=$pass){
              echo "<br>パスワードが違います<br><br>";
            }
         }
       }

    //テキストファイルの中身を表示
        $sql = "SELECT * FROM keijiban";
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();
        foreach($result as $row){
           echo $row["id"] . " ";
           echo $row["name"] . " ";
           echo $row["comment"] . " ";
           echo $row["created"] . " ";
           echo $row["complete"] . "<br>";
       }
       echo "<hr>";
       
    ?>
    <form action="" method="post">
        <input type="hidden" name="hidden" value="<?php if(!empty($edit)) {echo $editno;} ?>">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edit)) {echo $editname;} ?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edit)) {echo $editcomment;} ?>"><br>
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit"><br><br>
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="deleteNo" value="削除"><br><br>
        <input type="number" name="editno" placeholder="編集対象番号"><br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="edit" value="編集">
    </form>
</body>
</html>