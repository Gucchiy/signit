<?
	include "./parts/db_initialize.php";
	include "./parts/function.php";
	
	session_start();
	
	$error_string = array();
	$message_string = array();

	if( !isset( $_GET["id"] ) || !isset( $_SESSION["id"] ) ){
		
		header( "Location:".URL_PLZ_SIGN );	
		exit();
	}
	
	$sig_id = $_GET["id"];
	$user_id = $_SESSION["id"];
	
	if( !is_numeric( $user_id ) || !is_numeric( $sig_id ) ){
		
		header( "Location:".URL_PLZ_SIGN );	
		exit();
	}
	
	$query = "SELECT COUNT(*), title FROM ".TN_SIG_MAIN." WHERE user_id='$user_id' AND id='$sig_id'";

	$result = mysql_query( $query, $db );
	$datas = mysql_fetch_array( $result );
	if( $datas[0] != 1 ){
		
		header( "Location:".URL_PLZ_SIGN );	
		exit();
	}
	$title = htmlspecialchars( $datas["title"] );
	
	$datas_array = array();
	$table_name = make_sig_table_name( $sig_id );
	$query = "SELECT id, nickname, name, email, address, comment, updated FROM ".$table_name." ORDER BY updated DESC";
	$result = mysql_query( $query, $db );
	if( $result ){

		while( $datas = mysql_fetch_array( $result ) ){

			array_push( $datas_array, $datas );		
		}

	}
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTlML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="ROBOTS" content="NOFOLLOW,NOINDEX" />
<meta name="ROBOTS" content="NONE" />
<title>ウェブ署名運動支援 PLZ-SIGN：データ出力：<?=$title?></title>
<link href="stylesheet/plz-sign.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">

<h1>ウェブ署名運動支援 PLZ-SIGN：データ出力</h1>
<h2><?=$title?></h2>
<?
	for( $i=0; $i < count( $error_string ); $i++ ){
	
		echo '<p style="font-style:bold;color:red">'.$error_string[$i]."</p>\n";
	}

	for( $i=0; $i < count( $message_string ); $i++ ){
	
		echo '<p>'.$message_string[$i]."</p>\n";
	}

?>

<table border="1" cellpadding="2" cellspacing="2">
<?
	for( $i=0; $i < count( $datas_array ); $i++ ){
	
		$datas = $datas_array[$i];
		$disp_nickname = htmlspecialchars( $datas["nickname"] );
		$disp_name = htmlspecialchars( $datas["name"] );
		$disp_email = htmlspecialchars( $datas["email"] );
		$disp_comment = htmlspecialchars( $datas["comment"] );
		$disp_updated = htmlspecialchars( $datas["updated"] );
		$disp_address = htmlspecialchars( $datas["address"] );
		echo "<tr>\n";
		echo "<td>".$disp_nickname."</td><td>".$disp_comment."</td>\n";
		echo "<td>".$disp_name."</td><td>".$disp_email."</td>\n";
		echo "<td>".$disp_address."</td><td>".$disp_updated."</td>\n";
		echo "</tr>\n";
	}
?>
</table>

<? include "./parts/footer.php" ?>
</div>
</body>
</html>
<?
	include "./parts/db_finalize.php";
?>