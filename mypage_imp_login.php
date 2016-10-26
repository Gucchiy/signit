<p>&nbsp;</p>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="sig">

<table style="margin-left:20px" class="inputform">
<tr>
<td bgcolor="#99CCCC">メールアドレス</td><td><input name="email" type="text" size="60" maxlength="255" value="<?=$email?>" ></td>
</tr>
<tr style="font-size:12px">
  <td bgcolor="#99CCCC">パスワード (8-32文字)</td>
  <td><input name="password" type="password" size="32" maxlength="32" value="<?=$password?>" /></td>
</tr>
<input name="command" type="hidden" value="mypage_login" />
<tr>
<td>
<input name="送信" type="submit" value="ログイン" />
</td>
</tr>
</table>
<p>&nbsp;</p>

</form>
