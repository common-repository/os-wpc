<?php
class OswpcPureClass{

	/*
	*  テーブル作成処理（主にインストール時に実行）
	*/
	/**
	**  OSWPC_TABLE
	**  data_id　データid
	**  name 設定名
	**  key キー
	**  delete_flag 削除フラグ
	**  create_time 作成日時、 update_time 更新日時
	**/
	public static function insDataTable(){

		$charset = defined("DB_CHARSET") ? DB_CHARSET : "utf8";
		$sql = "CREATE TABLE " .OSWPC_TABLE. " (\n".
				"`data_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n".
				"`name` varchar(255) NOT NULL,\n".
				"`key` varchar(100) NOT NULL,\n".
				"`delete_flag` int(1) NOT NULL DEFAULT '0',\n".
				"`create_time` datetime NOT NULL,\n".
				"`update_time` timestamp NOT NULL,\n".
				"PRIMARY KEY (`data_id`)\n".
			") ENGINE = MyISAM DEFAULT CHARSET=".$charset." AUTO_INCREMENT=1 \n";
		// SQL実行
		self::sql_performs($sql);

	}
	/**
	**  OSWPC_DETAIL_TABLE
	**  data_id　データid
	**  data_name データ名
	**  data_key データキー
	**  data データ内容
	**  user_role 紐づけるユーザ権限
	**  role_return 権限の一致TRUE(1) or FALSE(0)、TRUE=一致する、FALSE=一致しない
	****  説明:user_roleがXXという条件にするなら=TRUE、XX以外という条件にするなら=FALSE
	**  user_id 紐づけるユーザid
	**  id_return ユーザidの一致TRUE(1) or FALSE(0)、TRUE=一致する、FALSE=一致しない
	****  説明:user_idがXXという条件にするなら=TRUE、XX以外という条件にするなら=FALSE
	**  return 条件が一致するときの動作 0=何もしない、1=機能を有効、2=機能を無効
	**  delete_flag 削除フラグ
	**  create_time 作成日時、 update_time 更新日時
	**/
	public static function insDetailDataTable(){

		$charset = defined("DB_CHARSET") ? DB_CHARSET : "utf8";
		$sql = "CREATE TABLE " .OSWPC_DETAIL_TABLE. " (\n".
				"`detail_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n".
				"`data_id` bigint(20) NOT NULL DEFAULT '0',\n".
				"`data_name` varchar(255) NOT NULL,\n".
				"`data_key` varchar(100) NOT NULL,\n".
				"`data` text NOT NULL,\n".
				"`user_role` varchar(150) NOT NULL,\n".
				"`role_return` int(1) NOT NULL DEFAULT '0',\n".
				"`user_id` bigint(20) NOT NULL DEFAULT '0',\n".
				"`id_return` int(1) NOT NULL DEFAULT '0',\n".
				"`return` int(1) NOT NULL DEFAULT '0',\n".
				"`delete_flag` int(1) NOT NULL DEFAULT '0',\n".
				"`create_time` datetime NOT NULL,\n".
				"`update_time` timestamp NOT NULL,\n".
				"PRIMARY KEY (`detail_id`)\n".
			") ENGINE = MyISAM DEFAULT CHARSET=".$charset." AUTO_INCREMENT=1 \n";
		// SQL実行
		self::sql_performs($sql);

	}
	/*
	*  テーブル作成時に使用
	*/
	// テーブルの存在チェック
	public static function show_table($tbl){

		global $wpdb;
		return $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $tbl));

	}
	// sqlを操作するファイルを読み込み、sqlを実行
	public static function sql_performs($sql=''){

		// 関数が使用できなければファイル読み込み
		if(!function_exists('dbDelta')){
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		}

		return dbDelta($sql);

	}
}
/*
*  下記はプラグインのインストール時に使用する関数など ==============
*/
// プラグインが有効化されたときに実行する
function oswpc_activation_plugin(){

	// テーブルが存在しなければ作成
	if(!OswpcPureClass::show_table(OSWPC_TABLE)){
		OswpcPureClass::insDataTable();
	}
	// テーブルが存在しなければ作成
	if(!OswpcPureClass::show_table(OSWPC_DETAIL_TABLE)){
		OswpcPureClass::insDetailDataTable();
	}

}
// プラグイン有効化がなされたら
if(function_exists('register_activation_hook')){
	register_activation_hook(OSWPC_FILE, 'oswpc_activation_plugin');
}