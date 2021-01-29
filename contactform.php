<?php
  $kind   = array();
  $kind[1]  = '質問';
  $kind[2]  = 'ご意見';
  $kind[3]  = '資料請求';
  session_start();
  $mode = 'input';
  $errmessage = array();
  if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm'] ){
	  // 確認画面
    if( !$_POST['fullname'] ) {
	    $errmessage[] = "名前を入力してください";
    } else if( mb_strlen($_POST['fullname']) > 100 ){ 
	    $errmessage[] = "名前は100文字以内にしてください";
    }
	  $_SESSION['fullname']	= htmlspecialchars($_POST['fullname'], ENT_QUOTES);

	  if( !$_POST['email'] ) {
		  $errmessage[] = "Eメールを入力してください";
	  } else if( mb_strlen($_POST['email']) > 200 ){
		  $errmessage[] = "Eメールは200文字以内にしてください";
    } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
	    $errmessage[] = "メールアドレスが不正です";
	  }
    $_SESSION['email']	= htmlspecialchars($_POST['email'], ENT_QUOTES);
    
    if( !$_POST['mkind'] ) {
	    $errmessage[] = "種別を入力してください";
    } else if( $_POST['mkind'] <= 0 || $_POST['mkind'] >= 4 ){
	    $errmessage[] = "動作が不正です";
    }
	  $_SESSION['mkind']	= htmlspecialchars($_POST['mkind'], ENT_QUOTES);

	  if( !$_POST['message'] ){
		  $errmessage[] = "お問い合わせ内容を入力してください";
	  } else if( mb_strlen($_POST['message']) > 500 ){
		  $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
	  }
	  $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

	  if( $errmessage ){
	    $mode = 'input';
    } else {
      $token = bin2hex(random_bytes(32));
      $_SESSION['token'] = $token;
	    $mode = 'confirm';
    }
  } else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
      if( !$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email'] ){
      $errmessage[] = '不正な処理が行われました';
      $_SESSION     = array();
      $mode         = 'input';
    } else if( $_POST['token'] != $_SESSION['token'] ){
      $errmessage[] = '不正な処理が行われました';
      $_SESSION     = array();
      $mode         = 'input';
    } else {
    $message  = "お問い合わせを受け付けました \r\n"
              . "名前: " . $_SESSION['fullname'] . "\r\n"
              . "email: " . $_SESSION['email'] . "\r\n"
              . "種別: " . $kind[ $_SESSION['mkind'] ] . "\r\n"
              . "お問い合わせ内容:\r\n"
              . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
	  mail($_SESSION['email'],'お問い合わせありがとうございます',$message);
    mail('iwasatat.php@gmail.com','お問い合わせありがとうございます',$message);
    $_SESSION = array();
    $mode = 'send';
    }
  } else {
    $_SESSION['fullname'] = "";
    $_SESSION['email']    = "";
    $_SESSION['mkind']  = "";
    $_SESSION['message']  = "";
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>お問い合わせフォーム</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <style>
    body{
      padding: 10px;
      max-width: 600px;
      margin: 0px auto;
    }
    div.button{
      text-align: center;
    }
  </style>
</head>
<body>
  <?php if( $mode == 'input' ){ ?>
    <!-- 入力画面 -->
    <?php
      if( $errmessage ){
        echo '<div class="alert alert-danger" role="alert">';
        echo implode('<br>', $errmessage );
        echo '</div>';
      }
    ?>
    <form action="./contactform.php" method="post">
      名前    <input type="text"    name="fullname" value="<?php echo $_SESSION['fullname'] ?>" class="form-control" ><br>
      Eメール <input type="email"   name="email"    value="<?php echo $_SESSION['email'] ?>" class="form-control" ><br>
      種別：
      <select name="mkind" class="form-control">
        <?php foreach( $kind as $i => $v ){ ?>
          <?php if( $_SESSION['mkind'] == $i ) { ?>
          <option value="<?php echo $i ?>" selected><?php echo $v ?></option>
          <?php } else { ?>
          <option value="<?php echo $i ?>"><?php echo $v ?></option>
          <?php } ?>
        <?php } ?>
      </select><br>

      お問い合わせ内容<br>
      <textarea cols="40" rows="8" name="message" class="form-control" ><?php echo $_SESSION['message'] ?></textarea><br>
      <div class="button">
        <input type="submit" name="confirm" value="確認" class="btn btn-primary btn-lg"/>
      </div>
    </form>
  <?php } else if( $mode == 'confirm' ){ ?>
    <!-- 確認画面 -->
    <form action="./contactform.php" method="post">
      <input type="hidden" name="token" value= "<?php echo $_SESSION['token']; ?>">
      名前    <?php echo $_SESSION['fullname'] ?><br>
      Eメール <?php echo $_SESSION['email'] ?><br>
      種別    <?php echo $kind[ $_SESSION['mkind'] ] ?><br>
      お問い合わせ内容<br>
      <?php echo nl2br($_SESSION['message']) ?><br>
      <input type="submit" name="back" value="戻る" class="btn btn-primary"/>
      <input type="submit" name="send" value="送信" class="btn btn-primary"/>
    </form>
  <?php } else { ?>
    <!-- 完了画面 -->
    送信しました。お問い合わせありがとうございました。<br>
  <?php } ?>
</body>
</html>


