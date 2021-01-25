<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>お問い合わせフォーム</title>
</head>
<body>
  <?php if( $_POST ){ ?>
    <!-- 確認画面 -->
    <form action="./contactform.php" method="post">
      名前    <?php echo $_POST['fullname'] ?><br>
      Eメール  <?php echo $_POST['email'] ?></br>
      お問い合わせ内容</br>
      <?php echo nl2br($_POST['message']) ?><br>
      <input type="submit" name="back" value="戻る" />
      <input type="submit" name="send" value="送信" />
    </form>
  <?php } else { ?>
    <!-- 入力画面 -->
    <form action="./contactform.php" method="post">
      名前<input type="text" name="fullname" value=""><br>
      Eメール<input type="email" name＝"email" value=""></br>
      お問い合わせ内容</br>
      <textarea cols="40" rows="8" name="message"></textarea>
      <input type="submit" name="confirm" value="確認" />
    </form>
  <?php } ?>
</body>
</html>