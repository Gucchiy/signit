<?
	include "./parts/db_initialize.php";
	include "./parts/function.php";
	
	session_start();
	
	$error_string = array();
	$message_string = array();
	
	$state = "mypage_input_login";

	if( isset( $_SESSION["id"] ) && is_numeric( $_SESSION["id"] ) ){
	
		$state = "mypage_input";
	}
	
	if( isset( $_POST["command"] ) ){
		
		$state = $_POST["command"];
	
	}
	
	if( isset( $_SESSION["message"] ) && strlen( $_SESSION["message"] ) ){
	
			array_push( $message_string, $_SESSION["message"] );
			$_SESSION["message"] = "";
	}

	switch( $state ){
	case "mypage_login":
	
		$email = htmlspecialchars( $_POST["email"] );
		$password = htmlspecialchars( $_POST["password"] );
		
		if( !ereg("^[^@]+@[^.]+\..+", $_POST["email"] ) ){
		
			array_push( $error_string, "不正なメールアドレスです。" );
			$state = "mypage_input_login";
			break;
		}

		if( sig_login( $db, $email, $password, $error_string ) == true ){
			
			$state = "mypage_input";			
			
		}else{
		
			$state = "mypage_input_login";	
		}
		break;
		
	case "mypage_change_profile":
	
		if( !strlen( $_POST["nickname"] ) ){
			
			array_push( $error_string, "ニックネームが入力されていません。" );
		}

		if( !strlen( $_POST["name"] ) ){
			
			array_push( $error_string, "お名前が入力されていません。" );
		}
		
		$can_change_password = false;	
		$password_len = strlen( $_POST["password"] );
		if( $password_len  ){
		
			$can_change_password = true;	
		
			if( $password_len < 8 ){
			
				$can_change_password = false;
				array_push( $error_string, "パスワードが短すぎます。8文字以上指定してください。" );
				
			}
			
			if( $password_len > 32 ){
				
				$can_change_password = false;
				array_push( $error_string, "パスワードは32文字までを指定ください。" );
			
			}
			
			if( !strlen( $_POST["password2"] ) ){
			
				$can_change_password = false;
				array_push( $error_string, "パスワードの確認が入力されていません。" );		
			}
			
			if( $_POST["password"] != $_POST["password2"] ){
				
				$can_change_password = false;
				array_push( $error_string, "パスワードの入力内容が確認と異なります。" );		
			}
			
		}
		
		$nickname = htmlspecialchars($_POST["nickname"] );
		$nickname_db = mysql_escape_string( $nickname );
		$name = htmlspecialchars( $_POST["name"] );
		$name_db = mysql_escape_string( $name );
		
		$sql_string_password = "";
		if( $can_change_password ){
			
			$password = htmlspecialchars( $_POST["password"] );
			$password_db = mysql_escape_string( $password );
			$sql_string_password = ", password='$password_db'";
			$_SESSION["password"] = md5( $password );
			array_push( $message_string, "パスワードを変更しました。" );
		}
		$query = "UPDATE ".TN_SIG_USER." SET nickname='$nickname_db', name='$name_db'".$sql_string_password
					." WHERE id=".$_SESSION["id"];
		mysql_query( $query, $db );
		
		$_SESSION["nickname"] = $nickname;
		$_SESSION["name"] = $name;
		$state = "mypage_input";
		array_push( $message_string, "プロフィール情報を更新しました。" );
		
		break;
	
	case "mypage_change_sig":

		$sig_num = htmlspecialchars( $_POST["sig_num"] );
		$sig_num_db = mysql_escape_string( $sig_num );
		$title = htmlspecialchars( $_POST["title"] );
		$title_db = mysql_escape_string( $title );
		$comment = htmlspecialchars( $_POST["comment"] );
		$comment_db = mysql_escape_string( $comment );
		$url = htmlspecialchars( $_POST["url"] );
		$url_db = mysql_escape_string( $url );

		if( isset( $_POST["need_address"] ) ){
			
			$need_address = htmlspecialchars( $_POST["need_address"] );		
			$need_address_db = mysql_escape_string( $need_address );
		
		}else{
			
			$need_address = 0;
		}

		if( !is_numeric( $need_address ) || !is_numeric( $sig_num )){
			
			array_push( $error_string, "不正なパラメーターを検出し、更新に失敗しました。" );
			break;
		}
		
		$query = "UPDATE ".TN_SIG_MAIN." SET need_address='$need_address_db',"
					."title='$title_db', comment='$comment_db', url='$url_db' WHERE id='$sig_num'";
		mysql_query( $query, $db );
		array_push( $message_string, "ウェブ署名 [$title] データを更新しました。" );
			
		break;
		
	case "mypage_add_sig":

		$title = htmlspecialchars( $_POST["title"] );
		$title_db = mysql_escape_string( $title );
		$comment = htmlspecialchars( $_POST["comment"] );
		$comment_db = mysql_escape_string( $comment );
		$url = htmlspecialchars( $_POST["url"] );
		$url_db = mysql_escape_string( $url );	

		// 初期署名の登録
		$query = "INSERT INTO ".TN_SIG_MAIN." SET user_id='".$_SESSION["id"]."', title='$title_db', "
					."comment='$comment_db', url='$url_db', created=NOW()";
		mysql_query( $query, $db );

		// 最後に登録した ID → 署名 table id
		$result = mysql_query( "SELECT LAST_INSERT_ID()", $db );
		$datas_id = mysql_fetch_array( $result );
		$db_id = htmlspecialchars( $datas_id[0] );
		mysql_free_result( $result );
	
		$database_name = create_sig_table( $db, $db_id );

		break;
	
	case "mypage_logout":
	
		sig_logout();
		header( "Location:".URL_PLZ_SIGN );
		exit();
	}
		
	if( $state == "mypage_input" ){

		$login_id = $_SESSION["id"];
		$login_password = $_SESSION["password"];
		$login_email = $_SESSION["email"];
		$login_nickname = $_SESSION["nickname"];
		$login_name = $_SESSION["name"];
		$login_updated = $_SESSION["updated"];
		$login_created = $_SESSION["created"];
		
		$query = "SELECT id, status, need_address, title, comment, url, comment_last, updated, created "
			."FROM ".TN_SIG_MAIN." WHERE user_id='$login_id' ORDER BY created DESC";
		
		$result = mysql_query( $query, $db );

		$datas_sigs = array();
		if( $result ){
		
			while( $datas_main = mysql_fetch_array( $result ) ){
			
				array_push( $datas_sigs, $datas_main );
			}
			
		}
		
	}
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTlML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="ROBOTS" content="NOFOLLOW,NOINDEX" />
<meta name="ROBOTS" content="NONE" />
<title>ウェブ署名運動支援 PLZ-SIGN：マイページ</title>
<link href="stylesheet/plz-sign.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">
<?
	if( $state == "mypage_input" ){
		
		include "index_imp_logout.php";
	}
?>
<h1>ウェブ署名運動支援 PLZ-SIGN：マイページ</h1>
<?
	for( $i=0; $i < count( $error_string ); $i++ ){
	
		echo '<p style="font-style:bold;color:red">'.$error_string[$i]."</p>\n";
	}

	for( $i=0; $i < count( $message_string ); $i++ ){
	
		echo '<p>'.$message_string[$i]."</p>\n";
	}

	// print_r( $_SESSION );
	
	switch( $state ){
	case "mypage_input_login":
	
		include "mypage_imp_login.php";
		break;
	
	case "mypage_input":
	
		include "mypage_imp_input.php";
		break;
	
	}
?>


<? include "./parts/footer.php" ?>
</div>
</body>
</html>
<?
	include "./parts/db_finalize.php";
?>