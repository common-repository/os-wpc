<?php
/*
*  権限追加、編集Class
*/
class OswpcRole extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	// 権限編集
	public static function edit_role($roles){

		// 変更
		if(!empty($_POST['name'])){
			// 表示名を変更する
			if($detail_id = OswpcSql::insert_rolename_data($roles, $_POST)){ //成功なら次の処理へ
				$role_row = (isset($roles->roles)) ? $roles->roles: array();
				$roles_array = (isset($role_row['administrator']) && isset($role_row['administrator']['capabilities'])) ? $role_row['administrator']['capabilities']: array();
				$role_name_array = (isset($roles->role_names)) ? $roles->role_names: array();
				// 権限をチェックし、更新用配列を作成
				$update_role = array();
				// 作成
				foreach($role_name_array as $name => $val){
					if($name!='administrator'){
						$update_role[$name] = array();
						//
						foreach($roles_array as $cap => $c){
							// 現在、権限を保有しているのならtrue
							if(isset($role_row[$name]) && isset($role_row[$name]['capabilities']) && isset($role_row[$name]['capabilities'][$cap])){
								$cap_check = TRUE;
							}else{
								$cap_check = FALSE;
							}
							// 値1があれば有効にする
							if(isset($_POST[$cap]) && !empty($_POST[$cap][$name])){
								// 権限を保有していないのならば、1をいれておく
								if($cap_check===FALSE){
									$update_role[$name][$cap] = 1;
								}
							}else{ // なければ無効にする
								// 権限を保有しているなら、0をいれておく
								if($cap_check===TRUE){
									$update_role[$name][$cap] = 0;
								}
							}
						}
					}
				}
				// 権限処理
				if(!empty($update_role)){
					foreach($update_role as $name => $cap_array){
						$role = get_role($name);
						//
						foreach($cap_array as $cap => $c){
							if($c==1){ // 有効
								$role->add_cap($cap);
							}else{ // 無効
								$role->remove_cap($cap);
							}
						}
					}
				}
				return $detail_id;
			}else{
				return FALSE;
			}
		}else{ // POSTなし
			return FALSE;
		}

	}
	// 権限作成
	public static function new_role($capabilities='', $role='', $name=''){

		if(empty($capabilities) && !empty($_POST['capabilities'])){
			$capabilities = $_POST['capabilities'];
		}
		if(empty($role) && !empty($_POST['role'])){
			$role = $_POST['role'];
		}
		if(empty($name) && !empty($_POST['name'])){
			$name = $_POST['name'];
		}
		// 指定のCapabilitiesを取得して、
		$cap_role = get_role($capabilities);
		$new_cap = $cap_role->capabilities;
		// 新規作成
		$result = add_role($role, $name, $new_cap);
		// 成功
		if(null!==$result){
			return TRUE;
		}else{ // 失敗、重複
			return FALSE;
		}

	}
	// 権限削除
	public static function delete_role($delete_role=''){

		if(empty($delete_role) && !empty($_POST['role'])){
			$delete_role = $_POST['role'];
		}
		// 削除
		remove_role($delete_role);

	}
	// 権限グループ名の表示変更
	// 公開側でも適用される
	public static function change_role_name(){

		$roleData = OswpcSql::get_data('role_data');
		// データがあれば
		if(!empty($roleData)){
			global $wp_roles;
			if(!isset($wp_roles)){
				$wp_roles = new WP_Roles();
			}
			// 権限グループごとに処理
			foreach($roleData as $role => $data){
				// 表示名データがあれば
				if(!empty($data['role_name'])){
					$name = (isset($data['role_name']['data'])) ? $data['role_name']['data']: '';
					$wp_roles->roles[$role]['name'] = $name;
					$wp_roles->role_names[$role] = $name;
				}
			}
		}

	}
	// 管理者のroleをもとにチェックボックスを作成する
	public static function cap_checkbox($roles_r, $now_cap, $now_group){

		$checkbox_html = '';
		$cap_array = (isset($roles_r) && isset($roles_r['administrator']) && isset($roles_r['administrator']['capabilities'])) ? $roles_r['administrator']['capabilities']: array();
		//
		if(!empty($cap_array)){
			foreach($cap_array as $key => $cap){
				if(!preg_match('/level_([0-9]+)/u', $key, $m)){
					// 配列にキーがあるかチェック
					if(array_key_exists($key, $now_cap)){ // あれば
						$checked = (!empty($now_cap[$key])) ? 'checked': '';
					}else{ // なければ
						$checked = '';
					}
					//
					$checkbox_html .= '<input type="checkbox" name="'.esc_html($key).'['.$now_group.']" id="'.esc_html($key).'_'.$now_group.'" value="1" '.$checked.' /><label for="'.esc_html($key).'_'.$now_group.'">'.esc_html($key).'</label>&nbsp;&nbsp;';
				}
			}
		}

		return $checkbox_html;

	}

}