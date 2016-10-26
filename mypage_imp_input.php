<h2><?=$login_nickname?>さんのプロフィール</h2>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="sig">

<table style="margin-left:20px" class="inputform">
<tr>
<td bgcolor="#99CCCC">ニックネーム</td><td><input name="nickname" type="text" size="20" maxlength="255" value="<?=$login_nickname?>" />
<span class="red">※署名用連絡先として公開されます。</span></td>
</tr>
<tr>
<td bgcolor="#99CCCC">お名前</td><td><input name="name" type="text" size="20" maxlength="255" value="<?=$login_name?>" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">メール</td><td><?=$login_email?><br />
<span class="red">※署名用連絡先として公開されます。なお、メールアドレスは変更できません。</span></td>
</tr>
<tr style="font-size:12px">
  <td bgcolor="#99CCCC">パスワードの変更 (8-32文字)</td>
  <td><input name="password" type="password" size="32" maxlength="32" value="" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><span style="font-size:12px">パスワードの変更 (確認)</td><td><input name="password2" type="password" size="32" maxlength="32" value="" /></td>
</tr>
<input name="command" type="hidden" value="mypage_change_profile" />
<tr>
<td>
<input name="送信" type="submit" value="プロフィールの更新" />
</td>
</tr>
</table>

</form>

<h2>ウェブ署名一覧</h2>

<?
	for( $i=0; $i < count( $datas_sigs ); $i++ ){
	
		$datas_sig = $datas_sigs[$i];
		
		$sig_id = htmlspecialchars( $datas_sig["id"] );
		if( !is_numeric( $sig_id ) ){ continue; }
		$title = htmlspecialchars( $datas_sig["title"] );
		$comment = htmlspecialchars( $datas_sig["comment"] );
		$comment_last = htmlspecialchars( $datas_sig["comment_last"] );
		$url = htmlspecialchars( $datas_sig["url"] );
		$need_address = htmlspecialchars( $datas_sig["need_address"] );
		if( $need_address ){ $need_address_string = "checked='checked'"; }
		$sig_url = URL_PLZ_SIGN."sig.php?id=".$sig_id;
		
?>

<p>署名ページ：&nbsp;<a href="<?=$sig_url?>" target="_blank"><?=$sig_url?></a></p>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="sig">

<table style="margin-left:20px" class="inputform">
<tr>
<td bgcolor="#99CCCC">タイトル</td><td><input name="title" type="text" size="60" maxlength="60" value="<?=$title?>" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">概要</td><td><input name="comment" type="text" size="60" maxlength="256" value="<?=$comment?>" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">参考URL</td><td><input name="url" type="text" size="60" maxlength="256" value="<?=$url?>" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">住所の入力</td>
<td><input name="need_address" type="checkbox" id="need_address" value="1" <?=$need_address_string?> /></td>
</tr>
<input name="command" type="hidden" value="mypage_change_sig" />
<input name="sig_num" type="hidden" value="<?=$sig_id?>" />
<tr>
<td>
<input name="送信" type="submit" value="データ更新" /> <input name="署名一覧表示" value="署名一覧表示" type="button" onclick="window.open('disp_data.php?id=<?=$sig_id?>')"/>

</td>
</tr>
</table>

</form>

<hr />

<?
	}
?>

<h2>新たにウェブ署名を作る</h2>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="sig">

<table style="margin-left:20px" class="inputform">
<tr>
<td bgcolor="#99CCCC">タイトル</td><td><input name="title" type="text" size="60" maxlength="60" value="" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">概要</td><td><input name="comment" type="text" size="60" maxlength="256" value="" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">参考URL</td><td><input name="url" type="text" size="60" maxlength="256" value="" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">住所の入力</td>
<td><input name="need_address" type="checkbox" id="need_address" value="1" /></td>
</tr>
<input name="command" type="hidden" value="mypage_add_sig" />
<tr>
<td>
<input name="作成" type="submit" value="作成" />

</td>
</tr>
</table>

</form>
