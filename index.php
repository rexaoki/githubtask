<?php
session_start();
require('../dbconnect.php');

$image = date('YmdHis') . $_FILES['image']['name'];//20190413000000aoki.png
move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
$_SESSION['join'] = $_POST;
$_SESSION['join']['image'] = $image;



?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="check.php" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="name" size="35" maxlength="255" 
			value="<?php print(htmlspecialchars($_SESSION['name'], ENT_QUOTES)); ?>" />
			<?php if($_SESSION['error'][0] === 'blank'):?>
			<p class="error">* ニックネームを入力してください</p>
			<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" 
			value="<?php print(htmlspecialchars($_SESSION['email'], ENT_QUOTES)); ?>" />
			<?php if($_SESSION['error'][1] === 'blank'):?>
			<p class="error">* メールアドレスを入力してください</p>
			<?php endif; ?>
			<?php if($_SESSION['error'][4] === 'duplicate'):?>
			<p class="error">* 指定されたメールアドレスは、既に登録されています</p>
			<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" 
			value="<?php print(htmlspecialchars($_SESSION['password'], ENT_QUOTES)); ?>" />
			<?php if($_SESSION['error'][4] === 'length'):?>
			<p class="error">* パスワードは4文字以上で入力してください</p>
			<?php endif; ?>
			<?php if($_SESSION['error'][3] === 'blank'):?>
			<p class="error">* パスワードを入力してください</p>
			<?php endif; ?>
        </dd>
		<dt>写真など</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
			<?php if($_SESSION['error'][5] === 'type'):?>
			<p class="error">* 写真などは「.gif」または「.jpg」「.png」の画像を指定してください</p>
			<?php endif; ?>

        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>

