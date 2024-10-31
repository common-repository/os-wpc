<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>管理画面の制限</h2>
			<div class="oswpc-contents">
				現在の権限で閲覧可能なものをアクセス制限する。<br />
				当プラグインは権限を直接編集するのではなく、権限によって制限をかけますので、プラグインを無効にしたら制限は解除されます。
				<p>チェックマーク=非表示及びアクセス不可、チェックマークなし=表示及びアクセス可</p>
				<div class="red"><?php echo $message; ?></div>
				<form action="admin.php?page=os-wpc-admin.php&amp;mode=admin_menu" method="POST">
					<table class="fnt13">
<?php
					foreach($roles->roles as $key => $r){
						// 管理者権限以外は設定可能にする
						if($key!='administrator'){
							$role_name = $myClass->rolename($key, $r);
							$td = $myClass->admin_td_data($key, $r, $data);
?>

					<tr>
						<th><?php echo esc_html($role_name); ?> (<?php echo esc_html($key); ?>)</th>
						<td><?php echo $td; ?></td>
					</tr>

<?php
						}
					}
?>
					</table>
					<?php wp_nonce_field($action, '_wpnonce', false); echo "\n"; ?>
					<input type="hidden" name="mode" value="admin_menu" />
					<div class="submit">
						<input type="submit" name="submit" value="更新する" />
					</div>
				</form>
			</div>
		</div>
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-foot.php"); ?>
	</div>

<?php
}
?>