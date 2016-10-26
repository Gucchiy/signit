<table style="margin-left:20px" class="inputform">

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" name="sig">
<tr>
<td bgcolor="#99CCCC">ニックネーム</td><td><input name="nickname" type="text" size="20" maxlength="255" value="<?=$nickname?>" />
<span class="red">※署名用連絡先として公開されます。</span></td>
</tr>
<tr>
<td bgcolor="#99CCCC">お名前</td><td><input name="name" type="text" size="20" maxlength="255" value="<?=$name?>" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">メール</td><td><input name="email" type="text" size="60" maxlength="255" value="<?=$email?>" /><br />
<span class="red">※署名用連絡先として公開されます。</span></td>
</tr>
<tr>
<td bgcolor="#99CCCC">ウェブ署名のタイトル</td><td><input name="title" type="text" size="60" maxlength="60" value="<?=$title?>" /></td>
</tr>
<tr style="font-size:12px">
  <td bgcolor="#99CCCC">パスワード (8-32文字)</td>
  <td><input name="password" type="password" size="32" maxlength="32" value="<?=$password?>" /></td>
</tr>
<tr>
<td bgcolor="#99CCCC">パスワード (確認)</td><td><input name="password2" type="password" size="32" maxlength="32" value="<?=$password2?>" /></td>
</tr>
<input name="command" type="hidden" value="sig_add" />
<tr>
<td>
<input name="送信" type="submit" value="ウェブ署名の作成" />
</td>
</tr>
</form>
</table>

<h2>ウェブ署名運動支援 PLZ-SIGN について</h2>

<p>本サービスは、Google社への被災地動物の保護情報を集約する<a href="http://japan.animal-finder.appspot.com/">Animal Finder</a>の要望署名運動用に作成されたウェブ署名プログラムを
他の署名運動でも活用できるようにしたものです。
Animal Finder署名運動とは、<a href="http://blogs.yahoo.co.jp/tom_tamiya">おひとりさまとパグな日々</a>のTanyanさんが <a href="http://blogs.yahoo.co.jp/tom_tamiya/2505984.html">blog を母体に始めた活動</a>で、<a href="http://idea-tech.sakura.ne.jp/signature/">要望署名は3000件を超えました</a>。この署名運動で Google社はAnimal Finderの実装へ動いたのです。私はこの経験から、ウェブ上の署名でも何がしかの影響力を大企業や政策にも与えられるのではないか、と考えるようになりました。</p>
<p>本サービスは、ウェブ署名を無料で手軽に始めることができます。
blogなどを母体として署名活動を行うようなケースを想定しており、「ウェブ署名コミュニティ」的な横の連携は行いません。一つ一つ、ご自身の署名運動ごとに独立してウェブ署名を用意することが可能です。また、署名をする方はハンドル名ベースで署名を行うことができます。なお、近いうちにウェブ署名発起人が署名へのリンク元を確認できるアクセスログ機能を設ける予定です。
ご利用にあたっては下記の免責事項もご参照ください。</p>

<h2>ご利用にあたっての免責事項</h2>

<p>本サービスを利用して収集される署名について、署名を収集する発起人（以下発起人）の方がその管理の全責任を追うものとし、
本サービスを運営する私 <a href="mailto:Gucchiy@gmail.com">Gucchiy</a> は
情報セキュリティ上のいかなる責任も負わないものと致します。
プログラム上のセキュリティについては最善を尽くしておりますが、無料且つ個人で運営していると言う実情から
その限界があることをご理解ください。
また、システムはSAKURAサーバーを利用しており、システムとしての堅牢性については、
該当サービスが保守する範囲以上のことはサービス運営側では提供できませんことも、併せてご理解ください。</p>
<p>公序良俗に反するもの、法律に反すると判断された場合、
発起人の許可なく警察等へ収集データの提供を行えるものとします。</p>

<h2>ご要望等の受付</h2>

<p>本サービスは、<a href="http://idea-tech.sakura.ne.jp/">アイディア&amp;&amp;テクノロジー</a>、<a href="mailto:Gucchiy@gmail.com">Gucchiy</a> が運営しています。 
改善要望、バグのご報告等は、<a href="mailto:Gucchiy@gmail.com">Gucchiy@gmail.com</a> までどうぞ。</p>
<p>なお、署名用途に応じた大きなカスタマイズ等は、有料にて承ります。ご相談ください。
</p>

<p>&nbsp; </p>
