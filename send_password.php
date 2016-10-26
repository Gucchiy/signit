<?
	include "./parts/db_initialize.php";
	include "./parts/function.php";
/*	

	$nickname = htmlspecialchars( $_POST["nickname"] );
	$nickname_db = make_db_string( $_POST["nickname"] );
	$name = htmlspecialchars( $_POST["name"] );
	$name_db = make_db_string( $_POST["name"] );
	$email = htmlspecialchars( $_POST["email"] );
	$email_db = make_db_string( $_POST["email"] );
	$title = htmlspecialchars( $_POST["title"] );
	$title_db = make_db_string( $_POST["title"] );
	$password = htmlspecialchars( $_POST["password"] );
	$password_db = make_db_string( $_POST["password"] );

	
	$query = "REPLACE INTO ".TN_SIG_WAIT_CONFIRM
		." SET nickname='$nickname_db', name='$name_db', email='$email_db', password='$password_db',"
		." title='$title_db', token='$token_db'";
	mysql_query( $query, $db );
	
	$mail_subject = "ウェブ署名の作成";
	$mail_message =
		"ウェブ署名 PLZ-SIGN へようこそ、".$nickname."さん\n\n".
		"ユーザー登録とウェブ署名の作成を完了するには下記のURLへアクセスしてください。\n".
		URL_PLZ_SIGN."regist_complete.php?key=".$security_token."&email=".$email;	

	mb_send_mail( $email, $mail_subject, $mail_message, "From: noreply@plz-sign.com ");
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="robots" CONTENT="INDEX,FOLLOW">
<title>ウェブ署名運動支援 PLZ-SIGN</title>
<link href="stylesheet/plz-sign.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">
<h1>ウェブ署名運動支援 PLZ-SIGN：パスワードの送信</h1>
<p>&nbsp;</p>
<p>工事中…</p>
<p>&nbsp;</p>
<? include "./parts/footer.php" ?>
</div>
</body>
</html>
<?
	include "./parts/db_finalize.php";
?>