<?
	include "/home/signit/www/plz-sign/parts/db_initialize.php";
	include "/home/signit/www/plz-sign/parts/function.php";
	
//	$query = "SELECT *, count(*) as counter, DATE(updated) as date_period FROM `sig_access_log` WHERE (DATE(updated) < DATE(now())) AND (http_referer NOT LIKE '%plz-sign.com%') GROUP BY sig_id, http_referer, date_period ORDER BY counter DESC";
	$query = "SELECT *, count(*) as counter, DATE(updated) as date_period FROM `sig_access_log` WHERE (DATE(updated) < DATE(now())) AND (http_referer NOT LIKE '%plz-sign.com%') GROUP BY sig_id, http_referer, date_period";
	$result = mysql_query( $query, $db );
	while( $datas = mysql_fetch_array( $result ) ){
		
		$query = "INSERT INTO sig_access_analyze SET sig_id='".$datas["sig_id"]."', http_referer='".$datas["http_referer"]."'"
			.", counter='".$datas["counter"]."', date='".$datas["date_period"]."'";	
		mysql_query( $query, $db );	
	}
	mysql_free_result( $result );
	
	$query = "DELETE FROM sig_access_log WHERE DATE(updated) < DATE(NOW())";
	mysql_query( $query, $db );

	include "/home/signit/www/plz-sign/parts/db_finalize.php";
?>