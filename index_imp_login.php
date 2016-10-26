<div class="loginbox">
<form action="<?="mypage.php"?>" method="post" name="login">
<table class="inputform">
<tr>
	<td>メールアドレス</td>
</tr>
    <td> <input name="email" type="input" size="32" maxlength="256" /></td>
<tr>
	<td>パスワード</td>
</tr>
    <td> <input name="password" type="password" size="32" maxlength="32" /></td>
<tr>
    <td><input name="ログイン" type="submit" value="ログイン" /></td>
</tr>
</table>
<input name="command" type="hidden" value="mypage_login" />
</form>
</div>