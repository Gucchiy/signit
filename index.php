<?
	include "./parts/db_initialize.php";
	include "./parts/function.php";
	
	$error_string = array();
	
	$state = "sig_input";
	if( isset( $_POST["command"] ) ){
		$state = $_POST["command"];
	}
	
	if( isset( $_POST["送信"] ) && ( $_POST["送信"] == "修正する" ) ){
		
		$state = "sig_input";

		$nickname = htmlspecialchars( $_POST["nickname"] );
		$name = htmlspecialchars( $_POST["name"] );
		$email = htmlspecialchars( $_POST["email"] );
		$title = htmlspecialchars( $_POST["title"] );
		$password = htmlspecialchars( $_POST["password"] );
		$password2 = htmlspecialchars( $_POST["password2"] );
		echo "test:".$_POST["password2"];
	}
	
		
	switch( $state ){
	case "sig_add":
	
		if( !strlen( $_POST["nickname"] ) ){
			
			array_push( $error_string, "ニックネームが入力されていません。" );
		}

		if( !strlen( $_POST["name"] ) ){
			
			array_push( $error_string, "お名前が入力されていません。" );
		}

		if( !strlen( $_POST["email"] ) ){
			
			array_push( $error_string, "emailが入力されていません。" );
		}

		if( !ereg("^[^@]+@[^.]+\..+", $_POST["email"] ) ){
		
			array_push( $error_string, "不正なメールアドレスです。" );
		}
		
		if( !strlen( $_POST["title"] ) ){
			
			array_push( $error_string, "タイトルが入力されていません。" );
		}

		$password_len = strlen( $_POST["password"] );
		if( !$password_len  ){
			
			array_push( $error_string, "パスワードが入力されていません。" );
		
		}
		
		if( $password_len < 8 ){
		
			array_push( $error_string, "パスワードが短すぎます。8文字以上指定してください。" );
		
		}
		
		if( $password_len > 32 ){
			
			array_push( $error_string, "パスワードは32文字までを指定ください。" );
		
		}
		
		if( !strlen( $_POST["password2"] ) ){
		
			array_push( $error_string, "パスワードの確認が入力されていません。" );		
		}
		
		if( $_POST["password"] != $_POST["password2"] ){
			
			array_push( $error_string, "パスワードの入力内容が確認と異なります。" );		
		}

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
		$password2 = htmlspecialchars( $_POST["password2"] );

		// 2重登録をチェック				
		$query = "SELECT COUNT(*) FROM ".TN_SIG_USER." WHERE email='$email_db'";
		$result = mysql_query( $query, $db );
		if( $result ){
	
			$datas_return = mysql_fetch_array( $result );
			if( $datas_return[0] != 0 ){
			
				array_push( $error_string, "既に登録されています。" );
			}
			mysql_free_result( $result );
		}				
		
		if( !count( $error_string ) ){
		// 確認状態に遷移
		
			$password_disp = str_pad( "", strlen( $password ), '*' );
			$state = "sig_confirm";

		}else{

			$state = "sig_input";	
		}
		break;
	
	case "sig_send":
	// 仮登録処理→メール送信

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

		require_once "lib_RandomString.php";
	
		
		// ランダム文字列生成クラス
		$rnd_string = new RandomString;
	
		$security_token = $rnd_string->number( 39 );
		$token_db = make_db_string( $security_token );
	
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
		
		break;
	
	default:
	
	}
	
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
<?
	if( $state == "sig_input" ){
		
		include "index_imp_login.php";
	}
?>
<h1>ウェブ署名運動支援 PLZ-SIGN αversion</h1>

<p><strong>どなたでもお手軽にウェブ署名を始めることができます。</strong></p>
<p><strong>&gt;&gt; <a href="http://www.plz-sign.com/sig.php?id=0000013" target="_blank">サンプル</a></strong>&nbsp;(<a href="http://idea-tech.sakura.ne.jp/signature/" target="_blank">Google Animal Finder 要望ウェブ署名の例</a>)</p>
<?
	switch( $state ){
	case "sig_input":
	// 入力状態
		
		for( $i=0; $i < count( $error_string ); $i++ ){
		
			echo '<p style="font-style:bold;color:red">'.$error_string[$i]."</p>\n";
		}
		
		include "index_imp_input.php";
		break;
		
	case "sig_confirm":
	// 入力確認	
		include "index_imp_confirm.php";
		break;
		
	case "sig_send":
	// メール送信
		include "index_imp_send.php";
		break;
	}
	
	sig_stamp_log( $db, 0 );

?>


<? include "./parts/footer.php" ?>
</div>
</body>
</html>
<?
	include "./parts/db_finalize.php";
?>