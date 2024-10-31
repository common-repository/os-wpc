<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>権限の追加・編集</h2>
			<div class="oswpc-contents">
				<h3>権限の編集</h3>
				<p>権限については、<a href="https://wpdocs.osdn.jp/%E3%83%A6%E3%83%BC%E3%82%B6%E3%83%BC%E3%81%AE%E7%A8%AE%E9%A1%9E%E3%81%A8%E6%A8%A9%E9%99%90" target="_blank">WordPress Codex 日本語版</a>をご確認ください。</p>
				<form action="admin.php?page=os-wpc-admin.php&amp;mode=role_edit" method="POST">
					<div class="red">
						<?php echo $message; ?>
					</div>
					<table class="fnt13">
					<tr>
						<th rowspan="2">権限グループ名</th>
						<th>表示名</th>
					</tr>
					<tr>
						<th>権限</th>
					</tr>
<?php
					// 処理
					foreach($roles->roles as $key => $r){
						// 管理者権限以外は設定可能にする
						if($key!='administrator'){
							$role_name = (isset($r['name'])) ? $r['name']: '';
?>
					<tr>
						<th rowspan="2"><?php echo esc_html($key); ?></th>
						<td><input type="text" name="name[<?php echo esc_html($key); ?>]" value="<?php echo esc_html($role_name); ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $myClass->cap_checkbox($roles->roles, $r['capabilities'], $key); ?></td>
					</tr>
<?php
						}
					}
					?>
					</table>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<input type="hidden" name="mode" value="role_edit" />
					<input type="hidden" name="edit_mode" value="edit" />
					<div class="submit">
						<input type="submit" name="submit" value="更新する" />
					</div>
				</form>
				<br />
				<a name="roleAdd" id="roleAdd"></a>
				<h3>権限グループの追加</h3>
				<p>権限グループ名は英数字でお願いします。</p>
				<form action="admin.php?page=os-wpc-admin.php&amp;mode=role_edit" method="POST">
					<div class="red">
						<?php echo $message2; ?><?php OswpcValidation::output_msg($validate); ?>
					</div>
					<label for="role">権限グループ名 : </label>
					<input type="text" name="role" id="role" value="" />
					<p>
					<label for="name">表示名 : </label>
					<input type="text" name="name" id="name" value="" />
					</p>
					<p>
					<label for="capabilities">コピー元 : </label>
					<select name="capabilities" id="capabilities">
<?php
					foreach($roles->roles as $key => $r){
						// 管理者権限以外は設定可能にする
						if($key!='administrator'){
							$role_name = $amClass->rolename($key, $r, 1);
							echo '<option value="'.esc_html($key).'">'.esc_html($role_name).'</option>';
						}
					}
?>
					</select>
					</p>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<input type="hidden" name="mode" value="role_edit" />
					<input type="hidden" name="edit_mode" value="add" />
					<div class="submit">
						<input type="submit" name="submit" value="追加する" />
					</div>
				</form>
				<br />
				<h3>権限グループの削除</h3>
				<form action="admin.php?page=os-wpc-admin.php&amp;mode=role_edit" method="POST">
					<p>
					<label for="delete-role">削除する権限グループ : </label>
					<select name="role" id="delete-role">
<?php
					foreach($roles->roles as $key => $r){
						// 管理者権限以外は設定可能にする
						if($key!='administrator'){
							$role_name = $amClass->rolename($key, $r, 1);
							echo '<option value="'.esc_html($key).'">'.esc_html($role_name).'</option>';
						}
					}
?>
					</select>
					</p>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<input type="hidden" name="mode" value="role_edit" />
					<input type="hidden" name="edit_mode" value="delete" />
					<div class="submit">
						<input type="button" value="削除する" onclick="del_check()" class="del-button" />
						<div id="final-ans" style="display:none;">
							<p>選択した権限グループを削除します。よろしいですか？</p>
							<span style="margin-right:20px;"><input type="submit" name="submit" value="実行する" /></span>
							<input type="button" value="キャンセル" onclick="del_cancel()" />
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-foot.php"); ?>
	</div>

<script>
// 削除確認
function del_check(){
	j('#final-ans').css('display', 'block');
	j('.submit .del-button').css('display', 'none');
}
// 削除キャンセル
function del_cancel(){
	j('#final-ans').css('display', 'none');
	j('.submit .del-button').css('display', 'inline-block');
}
</script>

<?php
}
?>