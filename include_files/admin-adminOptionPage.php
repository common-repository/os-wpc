<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>プラグイン設定</h2>
			<div class="oswpc-contents">
				<p>当プラグインを利用できる権限グループを設定します。<br>administrator（管理者権限グループ）は必ず利用できます。</p>
				<div class="red"><?php echo $message; ?></div>
				<form action="admin.php?page=os-wpc-option.php" method="POST">
					<div class="fnt13 form-box">
<?php
					$options = (isset($oswpc_options['edit_oswpc'])) ? $oswpc_options['edit_oswpc']: '';
					//
					foreach($role_names as $name => $r){
						if($name!='administrator'){
?>
						<span class="mright15"><input type="checkbox" name="edit_oswpc[<?php echo esc_html($name);?>]" id="edit_oswpc_<?php echo esc_html($name);?>" value="1" <?php if(!empty($options[$name])){ echo "checked"; } ?> /><label for="edit_oswpc_<?php echo esc_html($name);?>"><?php echo esc_html($r.' ('.$name.')');?></label></span>
<?php
						}
					}
?>
					</div>
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