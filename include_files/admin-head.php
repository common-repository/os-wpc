<?php
if(class_exists('OswpcAdmin')){
?>

		<div id="oswpc-header" class="clearfix">
			<div class="plugin-name">OS-WPカスタマイズプラグイン <?php echo OSWPC_VERSION; ?></div>
			<div class="header-link"><a href="http://lp.olivesystem.jp/category/wordpress-plugins" target="_blank">お知らせ・プラグイン情報</a> | <a href="http://lp.olivesystem.jp/wordpress" target="_blank">サイト制作</a> | <a href="http://lp.olivesystem.jp/wordpress%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3%E9%96%8B%E7%99%BA" target="_blank">プラグイン開発</a></div>
		</div>
		<ul class="plugin-list">
			<li class="first"><a href="admin.php?page=os-wpc-admin.php">はじめに</a></li>
			<li><a href="admin.php?page=os-wpc-admin.php&amp;mode=admin_menu">管理画面の制限</a></li>
			<li><a href="admin.php?page=os-wpc-admin.php&amp;mode=role_edit">権限の追加・編集</a></li>
			<li><a href="admin.php?page=os-wpc-accesslimit.php">表示制限コード</a></li>
			<li><a href="admin.php?page=os-wpc-customlogin.php">独自ログイン</a></li>
			<li><a href="admin.php?page=os-wpc-etc.php">その他カスタマイズ</a></li>
		</ul>

<?php
}
?>