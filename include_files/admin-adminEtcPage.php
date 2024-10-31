<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>その他カスタマイズ</h2>
			<div class="oswpc-contents">
				<p>ここでは細々としたカスタマイズができます。<br>既にテーマや他プラグインで設定されている場合は、そちらが優先される可能性があります。</p>
				<div class="red"><?php echo $message; ?></div>
				<form action="admin.php?page=os-wpc-etc.php" method="POST">
					<table class="fnt13 width-p100">
					<thead>
					<tr>
							<th colspan="2">投稿、タグに関するカスタマイズ</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th class="w150">記事の自動改行</th>
						<td>
							<input type="checkbox" name="remove_wpautop" id="remove_wpautop" value="1" <?php if(!empty($options['remove_wpautop'])){ echo 'checked'; } ?> /><label for="remove_wpautop">自動改行を解除</label>
						</td>
					</tr>
					<tr>
						<th>アイキャッチ機能</th>
						<td>
<?php
						if(!empty($post_types)){
							$pt_option = (isset($options['post_thumbnails'])) ? $options['post_thumbnails']: array();
							//
							foreach($post_types as $key => $m){
								$value = (isset($pt_option[$key])) ? $pt_option[$key]: 0;
?>
							<span class="mright15"><input type="checkbox" name="post_thumbnails[<?php echo esc_html($key); ?>]" id="<?php echo esc_html($key); ?>_thumbnails" value="1" <?php if(!empty($value)){ echo "checked"; } ?> /><label for="<?php echo esc_html($key); ?>_thumbnails"><?php echo esc_html($m); ?>ページ</label></span>
<?php
							}
						}
?>
						</td>
					</tr>
					<tr>
						<th>HTML5マークアップ<br>v3.9以上</th>
						<td>
<?php
						if(!empty($markup_target)){
							$hm_option = (isset($options['html5'])) ? $options['html5']: array();
							//
							foreach($markup_target as $key => $m){
								$value = (isset($hm_option[$key])) ? $hm_option[$key]: 0;
?>
							<span class="mright15"><input type="checkbox" name="html5[<?php echo esc_html($key); ?>]" id="<?php echo esc_html($key); ?>_html5" value="1" <?php if(!empty($value)){ echo "checked"; } ?> /><label for="<?php echo esc_html($key); ?>_html5"><?php echo esc_html($m); ?></label></span>
<?php
							}
						}
?>
						</td>
					</tr>
					<tr>
						<th>タイトルタグの自動出力<br>v4.1以上</th>
						<td>
							<input type="checkbox" name="title_tag" id="title_tag" value="1" <?php if(!empty($options['title_tag'])){ echo 'checked'; } ?> /><label for="title_tag">有効にする</label>
						</td>
					</tr>
					<tr>
						<th>RSSフィードのリンク</th>
						<td>
							<input type="checkbox" name="feed_links" id="feed_links" value="1" <?php if(!empty($options['feed_links'])){ echo 'checked'; } ?> /><label for="feed_links">有効にする</label>
						</td>
					</tr>
					</tbody>
					</table>
					<br>
					<table class="fnt13 width-p100">
					<thead>
					<tr>
						<th colspan="2">ウィジェット、メニューに関するカスタマイズ</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th class="w150">ウィジェット内<br>ショートコード</th>
						<td>
							<input type="checkbox" name="widget_shortcode" id="widget_shortcode" value="1" <?php if(!empty($options['widget_shortcode'])){ echo 'checked'; } ?> /><label for="widget_shortcode">有効にする</label>
						</td>
					</tr>
					<tr>
						<th>ナビゲーションメニュー</th>
						<td>
							<input type="checkbox" name="menus" id="menus" value="1" <?php if(!empty($options['menus'])){ echo 'checked'; } ?> /><label for="menus">有効にする</label>
						</td>
					</tr>
					</tbody>
					</table>
					<br>
					<table class="fnt13 width-p100">
					<thead>
					<tr>
						<th colspan="2">WordPressバージョン情報等のカスタマイズ</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<th class="w150">ソースコード上の<br>バージョン情報</th>
						<td>
							<input type="checkbox" name="not_public_wp_version" id="not_public_wp_version" value="1" <?php if(!empty($options['not_public_wp_version'])){ echo 'checked'; } ?> /><label for="not_public_wp_version">出力しない</label>
							<div>メタタグgenerator、フィード出力のバージョン情報を消します</div>
						</td>
					</tr>
					<tr>
						<th>管理画面上の<br>バージョンアップ通知</th>
						<td>
						<?php $value = (isset($options['not_version_up'])) ? $options['not_version_up']: 0; ?>
							<input type="radio" name="not_version_up" id="not_version_up0" value="0" <?php if($value==0){ echo 'checked'; } ?> /><label for="not_version_up0">通知する</label>&nbsp;&nbsp;
							<input type="radio" name="not_version_up" id="not_version_up1" value="1" <?php if($value==1){ echo 'checked'; } ?> /><label for="not_version_up1">通知しない</label>&nbsp;&nbsp;
							<input type="radio" name="not_version_up" id="not_version_up2" value="2" <?php if($value==2){ echo 'checked'; } ?> /><label for="not_version_up2">管理者のみに通知する</label>
						</td>
					</tr>
					<tr>
						<th>管理画面フッターの<br>バージョン情報</th>
						<td>
						<input type="checkbox" name="not_footer_version" id="not_footer_version" value="1" <?php if(!empty($options['not_footer_version'])){ echo 'checked'; } ?> /><label for="not_footer_version">表示しない</label>
						</td>
					</tr>
					<tr>
						<th>管理画面フッターの<br>サンクステキスト</th>
						<td>
						<input type="checkbox" name="admin_thanks_text_flag" id="admin_thanks_text_flag" value="1" <?php if(!empty($options['admin_thanks_text_flag'])){ echo 'checked'; } ?> /><label for="admin_thanks_text_flag">以下のテキストに変更する</label>
						<textarea name="admin_thanks_text"><?php if(!empty($options['admin_thanks_text'])){ echo esc_textarea($options['admin_thanks_text']); } ?></textarea>
						</td>
					</tr>
					</tbody>
					</table>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<div class="submit">
						<input type="submit" name="submit" value="設定する" />
					</div>
				</form>
			</div>
		</div>
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-foot.php"); ?>
	</div>

<?php
}
?>