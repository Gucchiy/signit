<?
	include "./parts/db_initialize.php";
	include "./parts/function.php";

	session_start();

	$error_string = array();
	$email = $_GET["email"];
	$key = $_GET["key"];
	
	if( !isset( $_GET["email"] ) || !isset( $_GET["key"] ) ){
		
		exit();
	}

	if( !ereg("^[^@]+@[^.]+\..+", $_GET["email"] ) ){
	// メールアドレスの妥当性確認
		array_push( $error_string, "不正なメールアドレスです。" );
	}
	
	// 仮登録DBの確認
	if( !count( $error_string ) ){
	
		$email_db = mysql_real_escape_string( $email );

		$query = "SELECT password, nickname, name, title, token FROM ".TN_SIG_WAIT_CONFIRM." WHERE email='$email_db'";
		$result = mysql_query( $query, $db );
		
		if( $result ){
			
			$datas_regist = mysql_fetch_array( $result );
				
			$password = htmlspecialchars( $datas_regist["password"] );
			$password_db =  mysql_real_escape_string( $password );
			$nickname = htmlspecialchars( $datas_regist["nickname"] );
			$nickname_db = mysql_real_escape_string( $nickname );
			$name = htmlspecialchars( $datas_regist["name"] );
			$name_db = mysql_real_escape_string( $name );
			$title = htmlspecialchars( $datas_regist["title"] );
			$title_db = mysql_real_escape_string( $title );
			$token = htmlspecialchars( $datas_regist["token"] );
			
			mysql_free_result( $result );
				
			if( $key != $token ){
			// token の確認
			
				// echo $key."<br />";
				// echo $token;
				
				array_push( $error_string, "古い登録URLを参照しています。<br />最後に届いたメールの登録URLをクリックください。" );
			}
			
		}
	
	}

	// 2重登録をチェック			
	if( !count( $error_string ) ){
	
		$query = "SELECT COUNT(*) FROM ".TN_SIG_USER." WHERE email='$email_db'";
		$result = mysql_query( $query, $db );
		if( $result ){
	
			$datas_return = mysql_fetch_array( $result );
			if( $datas_return[0] != 0 ){
			
				array_push( $error_string, "既に $email が登録されています。<br />"
					."お手数ですが、<a href='mypage.php'>マイページ</a>からログイン処理を行ってください。" );
			}
			mysql_free_result( $result );
		}	

	}

	// DB登録処理、署名DB作成と、仮DBからの削除
	if( !count( $error_string ) ){

		$query = "INSERT INTO ".TN_SIG_USER." SET password='$password_db', email='$email_db', ".
					"nickname='$nickname_db', name='$name_db', created=NOW()";
		mysql_query( $query, $db );
		
		// 最後に登録した ID → user id
		$result = mysql_query( "SELECT LAST_INSERT_ID()", $db );
		$datas_id = mysql_fetch_array( $result );
		$user_id = htmlspecialchars( $datas_id[0] );
		$user_id_db = mysql_real_escape_string( $user_id );
		mysql_free_result( $result );

		// 初期署名の登録
		$query = "INSERT INTO ".TN_SIG_MAIN." SET user_id='$user_id_db', title='$title_db', created=NOW()";
		mysql_query( $query, $db );

		// 最後に登録した ID → 署名 table id
		$result = mysql_query( "SELECT LAST_INSERT_ID()", $db );
		$datas_id = mysql_fetch_array( $result );
		$db_id = htmlspecialchars( $datas_id[0] );
		$db_id_db = mysql_real_escape_string( $user_id );
		mysql_free_result( $result );
		
		create_sig_table( $db, $db_id );

		// 登録待ち DB からの削除		
		$query = "DELETE FROM ".TN_SIG_WAIT_CONFIRM." WHERE email='$email_db'";			
		mysql_query( $query, $db );
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="ROBOTS" content="NOFOLLOW,NOINDEX" />
<meta name="ROBOTS" content="NONE" />
<title>ウェブ署名運動支援 PLZ-SIGN</title>
<link href="stylesheet/plz-sign.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">

<h1>ウェブ署名運動支援 PLZ-SIGN</h1>

<h2>登録完了</h2>

<?
	sig_login( $db, $email, $password, $error_string );
	for( $i=0; $i < count( $error_string ); $i++ ){
	
		echo '<p style="font-style:bold;color:red">'.$error_string[$i]."</p>\n";
	}
	
?>
<p></p>
<?

	if( !count( $error_string ) ){

?>
<p>ウェブ署名ページを以下の URL に作成致しました。</p>
<p>http://www.plz-sign.com/sig.php?id=<?=$db_id?></p>
<p></p>
<p>ウェブ署名のタイトルや説明を修正・追加するには、マイページをご利用ください。</p>
<p><a href="mypage.php">マイページ</a></p>
<p></p>

<?
	}		
?>

<? include "./parts/footer.php" ?>
</div>
</body>
</html>
<?
	include "./parts/db_finalize.php";
	if( !count( $error_string ) ){
	
		$_SESSION["message"] = "登録を完了し、ウェブ署名ページを作成しました。<br />"
			."このページはウェブ署名の管理者用ページです。ウェブ署名のタイトルなどを編集できます。<br />"
			."ブックマークしておくと便利です。";
		echo "<meta http-equiv='refresh' content='0;URL=mypage.php'>";
		
	}

?>
