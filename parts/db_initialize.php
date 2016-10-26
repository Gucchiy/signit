<?
	define( "DB_HOST", "mysql121.db.sakura.ne.jp" );
	define( "DB_USER", "signit" );
	define( "DB_PWD", "7742yama" );
	define( "DB_ENCD", "utf8" );
	define( "DB_NAME", "signit" );

	$db = mysql_connect( DB_HOST, DB_USER, DB_PWD ) 
		or die( "MySQL DB との接続に失敗しました" );
		
	mysql_query( "SET NAMES ".DB_ENCD, $db );	
	mysql_select_db( DB_NAME, $db );

	define( "TN_SIG_WAIT_CONFIRM", "sig_wait_confirm" );
	define( "TN_SIG_USER", "sig_user" );
	define( "TN_SIG_MAIN", "sig_main" );
	define( "TN_SIG_ACCESS_LOG", "sig_access_log" );
	define( "TN_SIG_Z", "sig_z" );
	define( "ST_SIG_GO", "0" );
	define( "ST_SIG_SUSPEND", 1 );
	define( "ST_SIG_OPEN_FINISH", 2 );
	define( "ST_SIG_CLOSE_FINISH", 3 );
	
	define( "URL_PLZ_SIGN", "http://plz-sign.com/" );

?>