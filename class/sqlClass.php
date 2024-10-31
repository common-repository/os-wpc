<?php
/*
*  SQL Class
*/
class OswpcSql extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	// データ取得
	public static function get_data($key, $role=''){

		$return_data = array();
		global $wpdb;
		$sql = "SELECT * FROM `".OSWPC_TABLE."` as `data`, `".OSWPC_DETAIL_TABLE."` as `detail` WHERE `data`.`key`=%s AND `data`.`delete_flag`=%d AND `data`.`data_id`=`detail`.`data_id` AND `detail`.`delete_flag`=%d";
		$params = array($key, 0, 0);
		// 権限の指定があれば
		if(!empty($role)){
			$sql .= " AND `detail`.`user_role`=%s";
			$params = array_merge($params, array($role));
		}
		$get_data = $wpdb->get_results( $wpdb->prepare($sql, $params), ARRAY_A);
		// データがあれば
		if(!empty($get_data)){
			$option_data = array();
			// 整理
			foreach($get_data as $get){
				$user_role = (isset($get['user_role'])) ? $get['user_role']: 0;
				$data_key = (isset($get['data_key'])) ? $get['data_key']: 0;
				//
				if(!isset($option_data[$user_role])){
					$option_data[$user_role] = array();
				}
				$option_data[$user_role][$data_key] = $get;
			}
			//
			if(!empty($role)){
				$return_data = $option_data[$role];
			}else{
				$return_data = $option_data;
			}
		}

		return $return_data;

	}
	/*
	*  管理画面制限の処理
	*/
	// 管理画面制限の情報取得
	public static function get_admin_menu($role=''){

		return self::get_data('admin_menu', $role);

	}
	// データが存在するかチェックし、存在すればupdate、なければinsert
	public static function insert_admin_menu_data($roles){

		global $wpdb;
		$now = date_i18n("Y-m-d H:i:s");
		// 権限ごとに処理していく
		foreach($roles->roles as $role_name => $r){
			// まず権限のデータがあるかチェック
			$data = self::get_admin_menu($role_name);
			$data_id = 0;
			// 権限のデータがあれば、data_id取得
			if(isset($data['dashboard']) && isset($data['dashboard']['data_id'])){
				$data_id = $data['dashboard']['data_id'];
			}else{ // なければインサートしてdata_id取得
				$sql = "INSERT INTO `".OSWPC_TABLE."` (`name`, `key`, `create_time`, `update_time`) VALUES (%s, %s, %s, %s)";
				$params = array($role_name.'の管理画面制限', 'admin_menu', $now, $now);
				$wpdb->query( $wpdb->prepare($sql, $params) );
				if($id = $wpdb->insert_id){
					$data_id = $id;
				}
			}
			// 取得できなければ中断
			if(empty($data_id)){
				return FALSE;
				break;
			}
			// 権限の詳細を取得して処理
			$capabilities = (isset($r['capabilities'])) ? $r['capabilities']: array();
			// ダッシュボードを先に処理したいので追加しておく
			$capabilities = array_merge(array('dashboard'=>1), $capabilities);
			// 寄稿者権限相当
			if(!empty($capabilities['level_1'])){
				$capabilities = array_merge($capabilities, array('jetpack'=>1,'tool'=>1));
			}
			// 投稿者権限相当
			if(!empty($capabilities['level_3'])){
				$capabilities = array_merge($capabilities, array('option'=>1));
			}
			// 編集者権限相当
			if(!empty($capabilities['level_3'])){
				$capabilities = array_merge($capabilities, array('feedback'=>1));
			}
			//
			foreach($capabilities as $cap => $val){
				switch($cap){
					// ダッシュボード（全ユーザ）
					case 'dashboard':
						$data_key = 'dashboard';
						break;
					// 投稿
					case 'edit_posts':
						$data_key = 'post';
						break;
					// コメント
					case 'moderate_comments':
						$data_key = 'comment';
						break;
					// メディア
					case 'upload_files':
						$data_key = 'media';
						break;
					// 固定ページ
					case 'edit_pages':
						$data_key = 'page';
						break;
					// プロフィール
					case 'read':
						$data_key = 'profile';
						break;
					// 外観
					case 'switch_themes':
						$data_key = 'theme';
						break;
					// プラグイン
					case 'edit_plugins':
						$data_key = 'plugin';
						break;
					// ユーザ
					case 'edit_users':
						$data_key = 'user';
						break;
					// ツール
					case 'tool':
						$data_key = 'tool';
						break;
					// Jetpack
					case 'jetpack':
						$data_key = 'jetpack';
						break;
					// 設定
					case 'option':
						$data_key = 'option';
						break;
					// 設定ツール
					case 'feedback':
						$data_key = 'feedback';
						break;
				}
				// データキーがあれば
				if(!empty($data_key)){
					// POSTデータ 1=管理画面制限を有効
					$flag = (isset($_POST[$data_key]) && !empty($_POST[$data_key][$role_name])) ? 1: 0;
					// 既存データチェック
					$existing = (isset($data[$data_key])) ? $data[$data_key]: '';
					// データがあればupdate
					if(!empty($existing['data_id']) && !empty($existing['detail_id'])){
						$sql = "UPDATE `".OSWPC_DETAIL_TABLE."` SET `user_role`=%s, `role_return`=%d, `return`=%d, `update_time`=%s WHERE `data_id`=%d AND `delete_flag`=%d AND `detail_id`=%d";
						$params = array($role_name, 1, $flag, $now, $existing['data_id'], 0, $existing['detail_id']);
						$return = $wpdb->query( $wpdb->prepare($sql, $params) );
						// 成功
						if($return!==FALSE){
							$detail_id = $existing['detail_id'];
						}else{ // 失敗なら中断
							return FALSE;
							break;
						}
					}else{ // なければinsert
						$sql = "INSERT INTO `".OSWPC_DETAIL_TABLE."` (`data_id`, `data_key`, `user_role`, `role_return`, `return`, `create_time`, `update_time`) VALUES (%d, %s, %s, %d, %d, %s, %s)";
						$params = array($data_id, $data_key, $role_name, 1, $flag, $now, $now);
						$wpdb->query( $wpdb->prepare($sql, $params) );
						// 成功
						if($id = $wpdb->insert_id){
							$detail_id = $id;
						}else{ // 失敗なら中断
							return FALSE;
							break;
						}
					}
					// アンセット
					unset($data_key);
				}
			}
		}

		return $detail_id;

	}
	/*
	*  権限の表示名
	*/
	// 権限の表示名管理画面制限の情報取得
	public static function get_role_name($role=''){

		return self::get_data('role_data', $role);

	}
	// http://www.nxworld.net/wordpress/wp-change-role-name.html
	// データが存在するかチェックし、存在すればupdate、なければinsert
	public static function insert_rolename_data($roles, $posts=''){

		global $wpdb;
		$now = date_i18n("Y-m-d H:i:s");
		$posts = (empty($posts) && !empty($_POST)) ? $_POST: $posts;
		// 権限ごとに処理していく
		foreach($roles->roles as $role_name => $r){
			// まず権限のデータがあるかチェック
			$data = self::get_role_name($role_name);
			$data_id = 0;
			$existing = '';
			// 権限のデータがあれば、data_id取得
			if(isset($data['role_name']) && isset($data['role_name']['data_id'])){
				$data_id = $data['role_name']['data_id'];
			}else{ // なければインサートしてdata_id取得
				$sql = "INSERT INTO `".OSWPC_TABLE."` (`name`, `key`, `create_time`, `update_time`) VALUES (%s, %s, %s, %s)";
				$params = array($role_name.'権限グループの設定', 'role_data', $now, $now);
				$wpdb->query( $wpdb->prepare($sql, $params) );
				if($id = $wpdb->insert_id){
					$data_id = $id;
				}
			}
			// 表示名データ
			if(isset($posts['name']) && !empty($posts['name'][$role_name])){
				$value = $posts['name'][$role_name];
				// 既存データチェック
				$data_key = 'role_name';
				$existing = (isset($data[$data_key])) ? $data[$data_key]: '';
				// データがあればupdate
				if(!empty($existing['data_id']) && !empty($existing['detail_id'])){
					$sql = "UPDATE `".OSWPC_DETAIL_TABLE."` SET `data`=%s, `user_role`=%s, `role_return`=%d, `return`=%d, `update_time`=%s WHERE `data_id`=%d AND `delete_flag`=%d AND `detail_id`=%d";
					$params = array($value, $role_name, 1, 1, $now, $existing['data_id'], 0, $existing['detail_id']);
					$return = $wpdb->query( $wpdb->prepare($sql, $params) );
					// 成功
					if($return!==FALSE){
						$detail_id = $existing['detail_id'];
					}else{ // 失敗なら中断
						return FALSE;
						break;
					}
				}else{ // なければinsert
					$sql = "INSERT INTO `".OSWPC_DETAIL_TABLE."` (`data_id`, `data_key`, `data`, `user_role`, `role_return`, `return`, `create_time`, `update_time`) VALUES (%d, %s, %s, %s, %d, %d, %s, %s)";
					$params = array($data_id, $data_key, $value, $role_name, 1, 1, $now, $now);
					$wpdb->query( $wpdb->prepare($sql, $params) );
					// 成功
					if($id = $wpdb->insert_id){
						$detail_id = $id;
					}else{ // 失敗なら中断
						return FALSE;
						break;
					}
				}
			}

		}

		if(!empty($detail_id)){
			return $detail_id;
		}else{
			return FALSE;
		}

	}
	/*
	*  エラー時の処理
	*/
	public static function error_behavior($wpdb){

		if(!empty($wpdb->last_error)){
			echo print_r($wpdb->last_error, true);
			exit;
		}

	}

}