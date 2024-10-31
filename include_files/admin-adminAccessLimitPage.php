<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>ページ表示制限のショートコード</h2>
			<div class="oswpc-contents">
				<p>現在この機能は<strong><?php if(!empty($options['access_limit'])){ echo "有効"; }else{ echo "無効"; } ?></strong>になっています。設定は下記フォームでできます。</p>
				<p>ショートコード<input type="text" value="[accessLimit user=ログインID]" readonly="readonly" style="width:225px;" />を記事内に設置することによって、記事へのアクセスをユーザ別またはユーザ権限グループ別に制限することができます。<br>詳しくは<a href="#setsumei">下記の説明</a>をご確認ください。</p>
				<div class="red"><?php echo $message; ?></div>
				<form action="admin.php?page=os-wpc-accesslimit.php" method="POST">
					<div class="fnt13 form-box">
					<?php
					if(!empty($options['access_limit'])){
						$checked0 = ''; $checked1 = 'checked';
					}else{
						$checked0 = 'checked'; $checked1 = '';
					}
					//
					$text = $myClass->limit_text();
					?>
						<span class="mright15"><strong>ユーザ別表示制限</strong>を</span>
						<span class="mright15"><input type="radio" name="access_limit" id="access_limit1" value="1" <?php echo $checked1; ?> /><label for="access_limit1">有効にする</label></span>
						<input type="radio" name="access_limit" id="access_limit0" value="0" <?php echo $checked0; ?> /><label for="access_limit0">無効にする</label>
						<p>
							<label for="limit_text" class="fweight">非表示時のテキスト</label><br>
							<textarea name="limit_text" id="limit_text"><?php echo esc_textarea($text); ?></textarea>
						</p>
					</div>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<div class="submit">
						<input type="submit" name="submit" value="設定する" />
					</div>
				</form>
				<a name="setsumei" id="setsumei"></a>
				<h3>説明</h3>
				<h3 class="mh">ユーザ別に制限する場合</h3>
				<pre><code>[accessLimit uid=ユーザid]</code> もしくは <code>[accessLimit user=ログインID]</code></pre>
				<p>
				ログインIDは<a href="users.php">ユーザーページ</a>でご確認ください。<br>
				<pre>	例：<code>[accessLimit user=hoge]</code></pre>
				<p>
				ユーザidはユーザ編集ページのURLで確認できます。user-edit.php?user_id=ここの数字。<br>
				カンマ区切りで複数のユーザを制限できます。
				</p>
				<pre>	例：<code>[accessLimit uid=1,3,4,7]</code></pre>
				<h3 class="mh">権限グループ別に制限する場合</h3>
				<pre><code>[accessLimit group=権限グループ名]</code></pre>
				<p>
				権限グループ名は<a href="admin.php?page=os-wpc-admin.php&mode=role_edit">権限の追加・編集</a>にある表を参考にしてください。
				</p>
				<pre>	例：<code>[accessLimit group=editor]</code></pre>
				<p>
				カンマ区切りで複数の権限グループを制限できます。
				</p>
				<pre>	例：<code>[accessLimit group=editor,author]</code></pre>
				<h3 class="mh">非表示ではなく表示する場合</h3>
				<p>
				通常は指定されたユーザ及び権限グループは非表示の扱いになりますが、<strong>view=on</strong>をつけることによって表示処理に変更できます。
				</p>
				<pre>	例：<code>[accessLimit group=editor view=on]</code></pre>
				<p>
				上記のショートコードの場合、editorグループはページが表示され、それ以外のグループは非表示になります。
				</p>
				<h3 class="mh">テキストを出力しない場合</h3>
				<p>
				通常は指定されたユーザ及び権限グループには非表示テキストが出力されますが、<strong>text=off</strong>をつけることによってテキストなしに変更できます。
				</p>
				<pre>	例：<code>[accessLimit group=author text=off]</code></pre>
				<p>
				上記のショートコードの場合、authorグループには記事内で何も出力されません。
				</p>
				<h3 class="mh">記事内にショートコード複数設置する場合</h3>
				<p>
				記事内にショートコードを複数設置する場合、必ず閉じタグ<strong>[/accessLimit]</strong>をつけてください。
				</p>
				<pre>	例：<code>[accessLimit group=author view=on]制限するコンテンツ1[/accessLimit]</code><br>	　　<code>ここはすべてのユーザに表示</code><br>	　　<code>[accessLimit group=editor view=on]制限するコンテンツ2[/accessLimit]</code></pre>
				<p>
				上記の場合、authorには制限するコンテンツ1、editorには制限するコンテンツ2が表示されます。
				</p>
				<h3 class="mh">ウィジェット内での使用</h3>
				<p>
				ウィジェットで当機能を使用すると、ユーザ別にメニューなどを制御することができます。<br>
				ウィジェット内で有効にする場合は、必ずウィジェットでショートコードが利用可能な状態にしてください。
				</p>
			</div>
		</div>
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-foot.php"); ?>
	</div>

<?php
}
?>