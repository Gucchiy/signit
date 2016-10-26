<?
// DB 用入力文字列を作成する
function make_db_string( $input_string )
{
	$input_string = htmlspecialchars( $input_string );
	return mysql_real_escape_string( $input_string );
}

function make_sig_table_name( $id )
{
	return TN_SIG_Z.str_pad( "$id", 7, "0", STR_PAD_LEFT );
}

function create_sig_table( $db, $id )
{
	$database_name = make_sig_table_name( $id );

	// 署名リストDBの作成
	$query =
		"CREATE TABLE `signit`.`$database_name` (".
		"`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,".
		"`nickname` VARCHAR( 64 ) NOT NULL ,".
		"`name` VARCHAR( 64 ) NOT NULL ,".
		"`email` VARCHAR( 256 ) NOT NULL ,".
		"`address` VARCHAR( 256 ) NOT NULL ,".
		"`comment` VARCHAR( 256 ) NOT NULL ,".
		"`password` VARCHAR( 16 ) NOT NULL ,".
		"`remote_host` VARCHAR( 256 ) NOT NULL ,".
		"`updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP".
		") ENGINE = MYISAM";
	mysql_query( $query, $db );
	
	return $databalse_name;
}

function sig_login( $db, $login_name, $password, &$error_string )
{
	$query = "SELECT id, password, email, nickname, name, updated, created FROM ".TN_SIG_USER." WHERE email='$login_name'";	
	
	$result = mysql_query( $query, $db );
	
	if( !$result ){
		
		array_push( $error_string, "$login_name は登録されていないメールアドレスです。[<b><a href='/'>登録する</a></b>]" );
		session_destroy();

		return false;
	}
	
	$datas_login = mysql_fetch_array( $result );
		
	if( count( $datas_login ) <= 1 ){
	
		array_push( $error_string, "$login_name は登録されていないメールアドレスです。[<b><a href='/'>登録する</a></b>]" );
		session_destroy();
		mysql_free_result( $result );

		return false;
	
	}
	
	if( $password != $datas_login["password"] ){
			
		array_push( $error_string, "パスワードが間違っています。".
			"[<b><a href='send_password.php'>パスワードを送信する</a></b>]" );
		session_destroy();
		mysql_free_result( $result );

		return false;
	}

	$_SESSION["id"] = htmlspecialchars( $datas_login["id"] );
	$_SESSION["password"] = md5( htmlspecialchars( $datas_login["password"] ) );
	$_SESSION["email"] = htmlspecialchars( $datas_login["email"] );
	$_SESSION["nickname"] = htmlspecialchars( $datas_login["nickname"] );
	$_SESSION["name"] = htmlspecialchars( $datas_login["name"] );
	$_SESSION["updated"] = htmlspecialchars( $datas_login["updated"] );
	$_SESSION["created"] = htmlspecialchars( $datas_login["created"] );
		
	mysql_free_result( $result );
		
	return true;	
}

function sig_logout()
{
	// セッション変数を全て解除する
	$_SESSION = array();
	 
	// セッションを切断するにはセッションクッキーも削除する。
	// Note: セッション情報だけでなくセッションを破壊する。
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	 
	session_destroy();
}

function sig_stamp_log( $db, $sig_id )
{	
	$remote_host = $_SERVER['REMOTE_HOST'];
	if( !strlen( $remote_host ) ){
		$remote_host = $_SERVER['REMOTE_ADDR'];
	}
	$query = "INSERT INTO ".TN_SIG_ACCESS_LOG." SET sig_id='$sig_id', "
				."http_user_agent='"
				.mysql_escape_string( $_SERVER['HTTP_USER_AGENT'] )
				."', remote_host='"
				.mysql_escape_string( $remote_host )
				."', http_referer='"
				.mysql_escape_string( $_SERVER['HTTP_REFERER'])."'";
	mysql_query( $query, $db );
	
}

?>