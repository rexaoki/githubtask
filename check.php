<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])){
	header('Location: index.php');
	exit();
}

$_SESSION['error'] = array(
	'name',
	'mailbl',
	'maildup',
	'passbl',
	'passlen',
	'imagetype',
	'imageemp' 
);
$errorcheck = 0;
// $_SESSION['error'] = array(
// 	'name',
// 	'mailbl',
// 	'maildup',
// 	'passbl',
// 	'passlen',
// 	'imagetype',
// 	'imageemp'
// );
//エラーがあった場合の処理
// $_SESSION['error'] = '';
// $_SESSION['error']['name'] = '';
// $_SESSION['error']['mail']= '';
// $_SESSION['error']['password'] = '';
// $_SESSION['error']['image'] = '';
// $error['mail'] = '';
// $error['email'] = '';
// $error['password'] = '';
// $error['image'] = '';

// 'name' => '',
// 'mailbl' => '',
// 'maildup' => '',
// 'passbl' => '',
// 'passlen' => '',
// 'imagetype' => '',
// 'imageemp' =>

if($_SESSION['name'] === ''){
	$_SESSION['error'][0] = 'blank';
	$errorcheck = 1;
	//$error['name'] = 'blank';
}
if($_SESSION['email'] === ''){
	$_SESSION['error'][1] = 'blank';
	$errorcheck = 1;
	//$error['email'] = 'blank';
}
if(strlen($_SESSION['password'] < 4)){
	$_SESSION['error'][4] = 'length';
	$errorcheck = 1;
	//$error['password'] = 'length';
}
if($_SESSION['password'] === ''){
	$_SESSION['error'][3] = 'blank';
	$errorcheck = 1;
	//$error['password'] = 'blank';
}
$fileName = $_FILES['image']['name'];
if(!empty($fileName)){
	$ext = substr($fileName, -3);
	if($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
		$_SESSION['error'][5] = 'type';
		$errorcheck = 1;
		//$error['image'] = 'type';
	}
}

	//アカウントの重複をチェック
	if(empty($_SESSION['error'])){
		$member = $db->prepare('SELECT COUNT(*) AS cnt
		FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cnt'] > 0){
			$_SESSION['error'][2] = 'duplicate';
			$errorcheck = 1;
		}
	}
	if(empty($errorcheck == 0)){
		$image = md5(uniqid(mt_rand(), true)) . $_FILES['image']['name'];//20190413000000aoki.png
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
	// header('Location: check.php');
	// exit();
	}
//エラーがなくポストに値が入っている場合
if(!empty($_POST) && $errorcheck == 0){
	$statement = $db->prepare('INSERT INTO members SET 
	name=?, email=?, password=?, picture=?, created=NOW()');
	$statement->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
		$_SESSION['join']['image']
	));
	//$statement = $db->query('SELECT * FROM members')
	unset($_SESSION['join']);

	header('Location: thanks.php');
	exit();
}else{
	header('Location: index.php');
}
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
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
	<dl>
		<dt>ニックネーム</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES));?>
        </dd>
		<dt>メールアドレス</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES));?>
        </dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<dt>写真など</dt>
		<dd>
		<?php if($_SESSION['join']['image'] !== ''): ?>
		<img src="../member_picture/<?php print(htmlspecialchars
		($_SESSION['join']['image'], ENT_QUOTES)); ?>">
		<?php endif; ?>
		</dd>
	</dl>
	<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</form>
</div>

</div>
</body>
</html>
