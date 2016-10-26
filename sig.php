<?
	include "parts/db_initialize.php";
	include "parts/function.php";
	
	// id=???? が必ず来る
	if( !isset( $_GET["id"] ) ){
		
		header( "Location:".URL_PLZ_SIGN );
		exit();
	}
	
	// 国際化コード
	$header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$lang = '';
	if(strpos($header,',')>0){
	
		$hds = explode(',', $header);
		$lang = $hds[0];
	}else{
		$lang = $header;
	}
	$lang = str_replace('-','_',$lang);		
	
	$str_nickname = 'Nickname<br />*will be displayed';
	$str_name = 'Name';
	$str_email = 'E-Mail';
	$str_comment = 'Comment<br />*will be displayed';
	$str_address = 'Address';
	$str_number = 'Cancel Numbers(4digit)';
	$str_submit = 'Submit';
		
	if( strstr($lang,'ja') ){
		$str_nickname = 'ニックネーム<br />※表示されます';
		$str_name = 'お名前&nbsp;';
		$str_email = 'メール';
		$str_comment = 'コメント<br />※表示されます';
		$str_address = '住所';
		$str_number = '解除パスワード(4文字)';
		$str_submit = '送信';
	}
	
	
	$id = $_GET["id"];
	
	$query = "SELECT title, comment, need_address, user_id, url FROM ".TN_SIG_MAIN." WHERE id='$id'";
	$result = mysql_query( $query, $db );
	if( !$result ){

		header( "Location:".URL_PLZ_SIGN );
		exit();
	}
	$datas_main = mysql_fetch_array( $result );
	$title = htmlspecialchars( $datas_main["title"] );
	$main_comment = htmlspecialchars( $datas_main["comment"] );
	$url = htmlspecialchars( $datas_main["url"] );
	$need_address = htmlspecialchars( $datas_main["need_address"] );
	$user_id = htmlspecialchars( $datas_main["user_id"] );
	mysql_free_result( $result );
	
	if( !is_numeric( $user_id ) ){
		header( "Location:".URL_PLZ_SIGN );
		exit();
	}
	
	$table_name = make_sig_table_name( $id );
	$action_string = $_SERVER['PHP_SELF']."?id=$id";
	
	$query = "SELECT nickname, email FROM ".TN_SIG_USER." WHERE id='$user_id'";
	$result = mysql_query( $query, $db );
	if( $datas_user = mysql_fetch_array( $result ) ){

		$proposer_nickname = htmlspecialchars( $datas_user["nickname"] );
		$proposer_email = htmlspecialchars( $datas_user["email"] );
	}
	mysql_free_result( $result );
	
	
	$error_string = array();
	
	if( isset( $_POST["command"] ) ){
		
		switch( $_POST["command"] ){
		case "sig_add":
		
			if( !strlen( $_POST["nickname"] ) ){
				
				array_push( $error_string, "ニックネームが入力されていません。Please input your nickname." );
			}

			if( !strlen( $_POST["name"] ) ){
				
				array_push( $error_string, "お名前が入力されていません。Please input your name." );
			}

			if( !strlen( $_POST["email"] ) ){
				
				array_push( $error_string, "emailが入力されていません。Please input your e-mail." );
			}

			if( !ereg("^[^@]+@[^.]+\..+", $_POST["email"] ) ){
			
				array_push( $error_string, "不正なメールアドレスです。" );
			}

			if( !strlen( $_POST["password"] ) ){
				
				array_push( $error_string, "解除パスワードが入力されていません。Please input a password for cancellation." );
			}
			
			$ng_words = array("http://","https://");
			// $ng_words = array("posterous.com","blogage.de","quadranet.com","pathfinder.gr","blog4ever.com","weblog.ro");
			foreach( $ng_words as $ng_word ){
						
				if( strstr( $_POST["comment"], $ng_word ) ){

					array_push( $error_string, "コメントにNGワードが含まれています。There are some NG words in comment." );
				}

			}
			

			$nickname = make_db_string( $_POST["nickname"] );
			$name = make_db_string( $_POST["name"] );
			$email = make_db_string( $_POST["email"] );
			$address = make_db_string( $_POST["address"] );
			$comment = make_db_string( $_POST["comment"] );
			$password = make_db_string( $_POST["password"] );

			// 2重登録を email と 名前の双方でチェック				
			$query = "SELECT COUNT(*) FROM ".$table_name." WHERE email='$email' AND name='$name'";
			$result = mysql_query( $query, $db );
			if( $result ){
		
				$datas_return = mysql_fetch_array( $result );
				if( $datas_return[0] != 0 ){
				
					array_push( $error_string, "既に署名が登録されています。Your data has been inputted already." );
				}
			}				

	
			if( !count( $error_string ) ){
				
				$remote_host = $_SERVER['REMOTE_HOST'];
				if( !strlen( $remote_host ) ){
					$remote_host = $_SERVER['REMOTE_ADDR'];
				}
				$remote_host = mysql_escape_string( $remote_host );
				
				$query = "INSERT ".$table_name." SET nickname='$nickname',name='$name',email='$email',"
							."address='$address', password='$password', comment='$comment', remote_host='$remote_host'";
				mysql_query( $query, $db );
				array_push( $error_string, "署名登録致しました。" );
			}
			break;
		
		case "sig_remove":

			$remnum = make_db_string( $_POST["remnum"] );
			$rempass = make_db_string( $_POST["rempass"] );
			
			if( $rempass == "9278" ){
				
				$query = "DELETE FROM ".$table_name." WHERE id='$remnum'";

			}else{
			
				$query = "DELETE FROM ".$table_name." WHERE id='$remnum' AND password='$rempass'";
			}

			if( mysql_query( $query, $db ) ){
			
				if( mysql_affected_rows( $db ) ){
					
					array_push( $error_string, "削除しました。" );	
			
				}else{
				
					array_push( $error_string, "削除パスワードが間違っています。" );	
				}
			}
			
			
			break;
		
		default:
		
		}
	}
	
	$datas_array = array();
	$count = 0;
	
	$query = "SELECT COUNT(*) FROM ".$table_name;
	$result = mysql_query( $query, $db );
	if( $result ){

		$datas_count = mysql_fetch_array( $result );
		$count = $datas_count[0];

	}		
	
	$max_page = floor( $count / 100 );
	$page = 0;
	
	if( isset( $_GET["page"] ) && is_numeric( $_GET["page"] ) ){
	
		$page = $_GET["page"];	
		if( $page > $max_page ){ $page = 0; }
		
	}
	$start_num = $page * 100;
	
	$query = "SELECT id, nickname, comment FROM ".$table_name." ORDER BY updated DESC LIMIT $start_num, 100";
	$result = mysql_query( $query, $db );
	if( $result ){

		while( $datas = mysql_fetch_array( $result ) ){

			array_push( $datas_array, $datas );		
		}

	}


	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META NAME="robots" CONTENT="INDEX,FOLLOW">
<title><?=$title?></title>
<link href="./stylesheet/plz-sign.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">

<h1><?=$title?></h1>
<p><strong><?=$main_comment?></strong></p>
<p>
<? 

	if( strlen( $url ) ){	
		if( $id == 159 ){
?>
<strong>&gt;&gt; <a href="<?=$url?>" target="_blank">旧ジョネス邸を次代に引き継ぐ会公式サイト</a></strong>
<?			
		}else{
?>
<strong>&gt;&gt; <a href="<?=$url?>" target="_blank">詳細記事</a></strong>
<?
		}
	}	
?>
<strong>&nbsp;&nbsp; 署名発起人：<a href="mailto:<?=$proposer_email?>"><?=$proposer_nickname?></a></strong>

</p>
<p style="font-size: 12px">注）一つのメールで、賛同して下さる家族全員エントリーできます。その場合、 同じメールアドレスで一人ずつ登録ください。<br />なお、お名前欄に複数のお名前を記入するのはご遠慮ください。</p>
<?
	for( $i=0; $i < count( $error_string ); $i++ ){
	
		echo '<p style="font-style:bold;color:red">'.$error_string[$i]."</p>\n";
	}

?>
<table style="margin-left:20px">

<form action="<?=$action_string?>" method="post" name="sig">
<tr>
<td bgcolor="#99CCCC"><?=$str_nickname?></td><td><input name="nickname" type="text" size="20" maxlength="255" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><?=$str_name?></td><td><input name="name" type="text" size="20" maxlength="255" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><?=$str_email?></td><td><input name="email" type="text" size="50" maxlength="255" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><?=$str_comment?></td><td><input name="comment" type="text" size="100" maxlength="255" /></td>
</tr>
<?
	if( $need_address ){
?>
<tr>
<td bgcolor="#99CCCC"><?=$str_address?></td><td><input name="address" type="text" size="100" maxlength="255" /></td>
</tr>
<?
	}
?>
<tr>
<td bgcolor="#99CCCC"><?=$str_number?></td><td><input name="password" type="password" size="8" maxlength="4" /></td>
</tr>
<input name="command" type="hidden" value="sig_add" />
<tr>
<td>
<input name="<?=$str_submit?>" type="submit" value="<?=$str_submit?>" />
</td>
</tr>
</form>
</table>

<?
	if( $count > 30 ){

		include "parts/ad-h-2.php";
	}
?>

<blockquote>
<b>現在までの署名一覧（<?=$count?>名）</b><br />


<form action="<?=$action_string?>" method="post" name="task_remove" style="padding:0px; margin:0px" class="inputform">

<table border="1" cellpadding="2" cellspacing="2">
<?
	for( $i=0; $i < count( $datas_array ); $i++ ){
	
		$datas = $datas_array[$i];
		$disp_nickname = htmlspecialchars( $datas["nickname"] );
		$disp_comment = htmlspecialchars( $datas["comment"] );
		echo "<tr>\n";
		echo "<td>".$disp_nickname."</td><td>".$disp_comment."</td>\n";
		?>
        <td width="100">
        	<input name='pass<?=$i?>' type='password' size='4' maxlength="4" />
	        <input type='submit' class="form_small_button" onclick="this.form.elements['rempass'].value=this.form.elements['pass<?=$i?>'].value;this.form.elements['remnum'].value='<?=$datas['id']?>'" value='削除' />
      </td></tr> 
        <?
	}
?>
</table>

<input name="remnum" type="hidden" value="" />
<input name="rempass" type="hidden" value="" />
<input name="command" type="hidden" value="sig_remove" />

</form>

<p>
<?
	if( $page > 0 ){
	
		echo "<a href='".$_SERVER['PHP_SELF']."?id=$id&amp;page=".($page-1)."'>前の100件 previous</a>";
		
	}else{

		echo "前の100件 previous";
	}
	
	echo "&nbsp;&nbsp;｜&nbsp;&nbsp;";
	
	if( $page < $max_page ){
	
		echo "<a href='".$_SERVER['PHP_SELF']."?id=$id&amp;page=".($page+1)."'>次の100件 next</a>";
	
		
	}else{
		
		echo "次の100件 next";
		
	}

	sig_stamp_log( $db, $id );
?>
</p>

</blockquote>

<? include "parts/ad-h-2.php" ?>

<? include "parts/footer.php" ?>
</body>
</html>
<?
	include "parts/db_finalize.php";
?>