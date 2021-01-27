<?php
  session_start();
  $mode = 'input';
  if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
    } else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      $_SESSION['fullname'] = $_POST['fullname'];
      $_SESSION['email'] = $_POST['email'];
      $_SESSION['message'] = $_POST['message'];
      $mode = 'confirm';
  } else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
    $message = "お問い合わせを受け付けました \r\n"
              . "名前: " . $_SESSION['fullname'] . "\r\n"
              . "email: " . $_SESSION['email'] . "\r\n"
              . "お問い合わせ内容:\r\n"
              . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
    mail($_SESSION['email'],'お問い合わせありがとうございます',$message);
    mail('on87.iwasaki.tatsuhiro@gmail.com','お問い合わせありがとうどざいます',$message);
    $_SESSION = array();
    $mode = 'send';
  } else {
    $_SESSION['fullname'] = "";
    $_SESSION['email'] = "";
    $_SESSION['message'] = "";
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>お問い合わせフォーム</title>
</head>
<body>
  <?php if( $mode == 'input' ){ ?>
    <!-- 入力画面 -->
    <form action="./contactform.php" method="post">
      名前<input type="text" name="fullname" value="<?php echo $_SESSION['fullname'] ?>"><br>
      Eメール<input type="email" name="email" value="<?php echo $_SESSION['email'] ?>"><br>
      お問い合わせ内容<br>
      <textarea cols="40" rows="8" name="message"><?php echo $_SESSION['message'] ?></textarea>
      <input type="submit" name="confirm" value="確認" />
    </form>
    <?php } else if( $mode == 'confirm' ){ ?>
    <!-- 確認画面 -->
    <form action="./contactform.php" method="post">
      名前    <?php echo $_SESSION['fullname'] ?><br>
      Eメール  <?php echo $_SESSION['email'] ?><br>
      お問い合わせ内容</br>
      <?php echo nl2br($_SESSION['message']) ?><br>
      <input type="submit" name="back" value="戻る" />
      <input type="submit" name="send" value="送信" />
    </form>
  <?php } else { ?>
  <?php } ?>
</body>
</html>