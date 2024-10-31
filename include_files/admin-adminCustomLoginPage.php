<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>独自ログインフォーム設定</h2>
			<div class="oswpc-contents">
				<p>ショートコード<input type="text" value="[customLogin]" readonly="readonly" style="width:120px;" />を貼りつけることによって、独自ログインフォームが利用できます。<br>詳しくは<a href="#setsumei">下記の説明</a>をご確認ください。</p>
				<div class="red"><?php echo $message; ?></div>
				<form action="admin.php?page=os-wpc-customlogin.php" method="POST">
					<table class="fnt13">
<?php
					$item = (isset($options['page'])) ? $options['page']: array();
					$value = (isset($item['redirect_to'])) ? $item['redirect_to']: 'referer';
?>
					<tr>
						<th colspan="2" class="tleft">記事内のログインフォーム&nbsp;&nbsp;<input type="text" value="[customLogin]" readonly="readonly" style="width:120px;font-weight:normal;" /></th>
					</tr>
					<tr>
						<th class="w100">ログイン後の<br>リダイレクト先</th>
						<td><input type="radio" name="page[redirect_to]" id="redirect_to0" value="referer" <?php if($value=='referer'){ echo 'checked'; } ?> /><label for="redirect_to0">リンク元</label>&nbsp;&nbsp;<input type="radio" name="page[redirect_to]" id="redirect_to1" value="top" <?php if($value=='top'){ echo 'checked'; } ?> /><label for="redirect_to1">トップページ</label>&nbsp;&nbsp;<input type="radio" name="page[redirect_to]" id="redirect_to2" value="admin" <?php if($value=='admin'){ echo 'checked'; } ?> /><label for="redirect_to2">管理画面</label></td>
					</tr>
<?php
					$value = (isset($item['logged'])) ? $item['logged']: 'none';
					$textarea_value = (isset($item['logged_textarea'])) ? $item['logged_textarea']: '';
					$textarea_value = str_replace(array('\"', "\'"), array('"', "'"), $textarea_value);
?>
					<tr>
						<th class="w100">ログイン済の<br>処理</th>
						<td>
						<input type="radio" name="page[logged]" id="logged0" value="none" <?php if($value=='none'){ echo 'checked'; } ?> /><label for="logged0">何も表示しない</label>&nbsp;&nbsp;<input type="radio" name="page[logged]" id="logged1" value="textarea" <?php if($value=='textarea'){ echo 'checked'; } ?> /><label for="logged1">テキストを表示</label>
							<div id="page-logged-textarea">
								<textarea name="page[logged_textarea]"><?php echo esc_textarea($textarea_value); ?></textarea><br><small>HTMLタグ使用可能</small>
							</div>
						</td>
					</tr>
<?php
					$item = (isset($options['widget'])) ? $options['widget']: array();
					$value = (isset($item['redirect_to'])) ? $item['redirect_to']: 'now';
?>
					<tr>
						<th colspan="2" class="tleft">ウィジェット内のログインフォーム&nbsp;&nbsp;<input type="text" value="[customLogin type=wd]" readonly="readonly" style="width:220px;font-weight:normal;" /></th>
					</tr>
					<tr>
						<th class="w100">ログイン後の<br>リダイレクト先</th>
						<td><input type="radio" name="widget[redirect_to]" id="w_redirect_to0" value="now" <?php if($value=='now'){ echo 'checked'; } ?> /><label for="w_redirect_to0">現在のページ</label>&nbsp;&nbsp;<input type="radio" name="widget[redirect_to]" id="w_redirect_to1" value="top" <?php if($value=='top'){ echo 'checked'; } ?> /><label for="w_redirect_to1">トップページ</label>&nbsp;&nbsp;<input type="radio" name="widget[redirect_to]" id="w_redirect_to2" value="admin" <?php if($value=='admin'){ echo 'checked'; } ?> /><label for="w_redirect_to2">管理画面</label></td>
					</tr>
<?php
					$value = (isset($item['logged'])) ? $item['logged']: 'none';
					$textarea_value = (isset($item['logged_textarea'])) ? $item['logged_textarea']: '';
					$textarea_value = str_replace(array('\"', "\'"), array('"', "'"), $textarea_value);
?>
					<tr>
						<th class="w100">ログイン済の<br>処理</th>
						<td>
						<input type="radio" name="widget[logged]" id="w_logged0" value="none" <?php if($value=='none'){ echo 'checked'; } ?> /><label for="w_logged0">何も表示しない</label>&nbsp;&nbsp;<input type="radio" name="widget[logged]" id="w_logged1" value="textarea" <?php if($value=='textarea'){ echo 'checked'; } ?> /><label for="w_logged1">テキストを表示</label>
							<div id="widget-logged-textarea">
								<textarea name="widget[logged_textarea]"><?php echo esc_textarea($textarea_value); ?></textarea><br><small>HTMLタグ使用可能</small>
							</div>
						</td>
					</tr>
					</table>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<div class="submit">
						<input type="submit" name="submit" value="設定する" />
					</div>
				</form>
				<a name="setsumei" id="setsumei"></a>
				<h3>説明</h3>
				<h3 class="mh">記事内にログインフォームを表示する場合</h3>
				<pre><code>[customLogin]</code></pre>
				<p>
				任意の投稿ページや固定ページに上記のショートコードを貼りつけることによって、そのページが独自のログインフォームがあるページになります。
				<p>
				<p>
				redirectを指定することによって、ログイン後のリダイレクト先を変更することができます。
				<pre>	例：<code>[customLogin redirect=hoge]</code></pre>
				</p>
				<h3 class="mh">ウィジェット内にログインフォームを表示する場合</h3>
				<pre><code>[customLogin type=wd]</code></pre>
				<p>
				ウィジェット内に上記のショートコードを貼りつけることによって、そのウィジェットが独自のログインフォームになります。ただし、<a href="admin.php?page=os-wpc-etc.php#widget_shortcode">ウィジェット内のショートコードが有効</a>になっている必要があります。
				</p>
				<p>
				"記事内のログインフォーム"と同様の機能になります。
				</p>
				<h3 class="mh">フォームのスタイルを変更する場合</h3>
				<p>
				フォームには"customlogin-form"というclassが設定されています。<br>
				記事内のフォームには"customlogin-form"というidが、ウィジェット内のフォームには"customlogin-widget-form"が設定されています。<br>
				フォームのスタイルを変更したい場合は、上記のclassやidを指定してスタイルシート等で修正してください。
				</p>
			</div>
		</div>
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-foot.php"); ?>
	</div>

<script>
function page_logged(){
	if(j('#logged0').is(':checked')){
		j('#page-logged-textarea').hide();
	}
	else{
		j('#page-logged-textarea').show();
	}
}
function widget_logged(){
	if(j('#w_logged0').is(':checked')){
		j('#widget-logged-textarea').hide();
	}
	else{
		j('#widget-logged-textarea').show();
	}
}
// 読み込み時の動作
j(document).ready(function(){
	// "記事内"の"ログイン済の処理"の設定
	page_logged();
	//
	j('input[name="page[logged]"]:radio').change(function(){
		page_logged();
	});
	// "ウィジェット内"の"ログイン済の処理"の設定
	widget_logged();
	//
	j('input[name="widget[logged]"]:radio').change(function(){
		widget_logged();
	});
});
</script>

<?php
}
?>