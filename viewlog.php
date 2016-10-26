<?
	include "./parts/db_initialize.php";
	include "./parts/function.php";
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="robots" CONTENT="INDEX,FOLLOW">
<title>ウェブ署名運動支援 PLZ-SIGN アクセスログ</title>
<link href="stylesheet/plz-sign.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div style="text-align:left">
<h1>ウェブ署名運動支援 PLZ-SIGN アクセスログ</h1>

<blockquote>
<table border="1" cellpadding="2" cellspacing="2">
<?
	$query = "SELECT * FROM sig_access_log ORDER BY updated DESC";
	$result = mysql_query( $query, $db );
	while( $datas = mysql_fetch_array( $result ) ){
?>
<tr>
<td><?=$datas["id"]?></td>
<td><?=$datas["sig_id"]?></td>
<td><?=$datas["remote_host"]?></td>
<td><?=$datas["http_user_agent"]?></td>
<td><?=$datas["http_referer"]?></td>
<td><?=$datas["updated"]?></td>
</tr>
<?
	}
	mysql_free_result( $result );
?>
</table>

</blockquote>

<? include "./parts/footer.php" ?>
</div>
</body>
</html>
<?
	include "./parts/db_finalize.php";
?>