<p style="font-style:bold;color:red">以下の内容でよろしいですか？</p>

<table style="margin-left:20px" style="font-size:12px">
<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="sig" style="font-size:12px">
<tr>
<td bgcolor="#99CCCC" style="font-size:12px">ニックネーム<br />※署名用連絡先として公開されます。</td>
<td><?=$nickname?></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><span style="font-size:12px">お名前</span></td>
<td><?=$name?></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><span style="font-size:12px">メール<br />※署名用連絡先として公開されます。</span></td>
<td><?=$email?></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><span style="font-size:12px">ウェブ署名のタイトル</span></td>
<td><?=$title?></td>
</tr>
<tr>
<td bgcolor="#99CCCC"><span style="font-size:12px">パスワード</span></td>
<td><?=$password_disp?></td>
</tr>
<input name="nickname" type="hidden" value="<?=$nickname?>" />
<input name="name" type="hidden" value="<?=$name?>" />
<input name="email" type="hidden" value="<?=$email?>" />
<input name="title" type="hidden" value="<?=$title?>" />
<input name="password" type="hidden" value="<?=$password?>" />
<input name="password2" type="hidden" value="<?=$password2?>" />
<input name="command" type="hidden" value="sig_send" />
<tr>
<td>
<input name="送信" type="submit" value="修正する" />
<input name="送信" type="submit" value="ウェブ署名の作成" />
</td>
</tr>
</form>
</table>
