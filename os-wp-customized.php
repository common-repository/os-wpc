<?php
/*
Plugin Name: OS-WPカスタマイズプラグイン
Plugin URI: http://lp.olivesystem.jp/
Description: WordPressの管理画面表示や設定の変更、ユーザ毎の表示切替、バージョン情報の非表示、自動改行解除など可能なプラグインです
Version: 1.5.0
Author: OLIVESYSTEM（オリーブシステム）
Author URI: http://lp.olivesystem.jp/
*/
if(!isset($wpdb)){
	global $wpdb;
}
// 現在のプラグインバージョン
define('OSWPC_VERSION','1.5.0');
// テーブル名
define('OSWPC_TABLE', $wpdb->prefix.'os_wpc_data');
define('OSWPC_DETAIL_TABLE', $wpdb->prefix.'os_wpc_detail_data');
// プラグインの設定をいれるオプション名
define('OSWPC_OPTIONS', 'os_wpc_options');
define('OSWPC_CL_OPTIONS', 'os_wpc_customlogin_options');
// このファイル
define('OSWPC_FILE', __FILE__);
// プラグインのディレクトリ
define('OSWPC_PLUGIN_DIR', plugin_dir_path(__FILE__));
// テキストメインのPHPファイルをいれているディレクトリ
define('OSWPC_INCLUDE_FILES', OSWPC_PLUGIN_DIR.'include_files');
// 関数
include_once(OSWPC_PLUGIN_DIR."class/commonClass.php");
include_once(OSWPC_PLUGIN_DIR."class/sqlClass.php");
include_once(OSWPC_PLUGIN_DIR."class/pureClass.php"); // 他の影響を受けないclass
include_once(OSWPC_PLUGIN_DIR."class/messageClass.php"); // メッセージclass
include_once(OSWPC_PLUGIN_DIR."class/validationClass.php"); // バリデーションclass
include_once(OSWPC_PLUGIN_DIR."oswpcValidation.php");
include_once(OSWPC_PLUGIN_DIR."oswpcAdmin.php");
include_once(OSWPC_PLUGIN_DIR."oswpcPublic.php");
$OswpcAdmin = new OswpcAdmin();
$OswpcPublic = new OswpcPublic();
