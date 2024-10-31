<?php
/*
*  管理画面の制限Class
*/
class OswpcAdminMenu extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	// 権限をチェックし、制限設定がなされていれば制限する
	public static function role_check_view(){

		global $menu; // メニュー
		global $submenu; // サブメニュー
		global $pagenow;
		$user = wp_get_current_user(); // ログインユーザデータ
		$role = (isset($user->roles) && isset($user->roles[0])) ? $user->roles[0]: '';
		$data = OswpcSql::get_admin_menu($role);
		// データがあれば
		if(!empty($data)){
			$hidden_view = array();
			//
			foreach($menu as $i => $m){
				if(isset($m[5])){
					switch($m[5]){
						// ダッシュボード
						case 'menu-dashboard': $key = 'dashboard'; break;
						// 投稿
						case 'menu-posts': $key = 'post'; break;
						// メディア
						case 'menu-media': $key = 'media'; break;
						// 固定ページ
						case 'menu-pages': $key = 'page'; break;
						// コメント
						case 'menu-comments': $key = 'comment'; break;
						// フィードバック
						case 'menu-posts-feedback': $key = 'feedback'; break;
						// プロフィール
						case 'menu-users':
							if(isset($m[2]) && $m[2]=='profile.php'){
								$key = 'profile';
							// ユーザ
							}elseif(isset($m[2])  && $m[2]=='users.php'){
								$key = 'user';
							}
							break;
						// 外観
						case 'menu-appearance': $key = 'theme'; break;
						// プラグイン
						case 'menu-plugins': $key = 'plugin'; break;
						// ツール
						case 'menu-tools': $key = 'tool'; break;
						// 設定
						case 'menu-settings': $key = 'option'; break;
						// Jetpack
						case 'toplevel_page_jetpack': $key = 'jetpack'; break;
					}
				}
				// 適用対象のキーがあれば
				if(!empty($key)){
					// 設定データをチェックし、有効ならば非表示にする
					foreach($data as $d){
						if(isset($d['data_key']) && $d['data_key']==$key){
							// 有効なのでメニュー非表示
							if(!empty($d['return'])){
								// ファイル名
								$filename = (isset($m[2])) ? $m[2]: 0;
								$hidden_view[] = $filename;
								// サブメニューの情報取得
								if(isset($submenu[$filename])){
									foreach($submenu[$filename] as $sub_array){
										// サブファイル名があれば
										if(isset($sub_array[2])){
											$subfilename = $sub_array[2];
											$hidden_view[] = $subfilename;
										}
									}
								}
								// メニュー非表示
								unset($menu[$i]);
								break;
							}
						}
					}
					unset($key);
				}
			}
			// ページを非表示
			if(!empty($hidden_view)){
				foreach($hidden_view as $pagename){
					$hidden = 0;
					// 
					if(stristr($pagename, "?")){
						$pagename_ex = explode("?", $pagename);
						parse_str($pagename_ex[1], $output);
						foreach($output as $key => $val){
							if(isset($_GET[$key]) && $val==$_GET[$key]){
								$hidden = 1;
							}else{
								$hidden = 0;
								break;
							}
						}
					}else{ // 入っていなければ、そのまま検証
						if($pagename==$pagenow){
							$hidden = 1;
						}
					}
					// エラーメッセージを表示
					if(!empty($hidden)){
						$message = '<p>表示エラー！</p><p>'.$pagename.'は表示できません</p>';
						wp_die($message, "表示エラー！");
						break;
					}
				}
			}
		}

	}
	// 権限名
	public static function rolename($key, $r, $type=''){

		if($type==1){
			$eng = $key.'&nbsp;';
		}else{
			$eng = '';
		}
		//
		switch($key){
			case 'subscriber':
				if(isset($r['name']) && $r['name']=='Subscriber'){
					$role_name = '購読者';
				}else{
					$role_name = (isset($r['name'])) ? $r['name']: '';
				}
				break;
			case 'contributor':
				if(isset($r['name']) && $r['name']=='Contributor'){
					$role_name = '寄稿者';
				}else{
					$role_name = (isset($r['name'])) ? $r['name']: '';
				}
				break;
			case 'author':
				if(isset($r['name']) && $r['name']=='Author'){
					$role_name = '投稿者';
				}else{
					$role_name = (isset($r['name'])) ? $r['name']: '';
				}
				break;
			case 'editor':
				if(isset($r['name']) && $r['name']=='Editor'){
					$role_name = '編集者';
				}else{
					$role_name = (isset($r['name'])) ? $r['name']: '';
				}
				break;
			default:
				$role_name = (isset($r['name'])) ? $r['name']: '';
		}

		return $eng.$role_name;

	}
	// 管理画面の制限の設定ページ用tdタグ
	public static function admin_td_data($key, $r, $data){

		$d = (isset($data[$key])) ? $data[$key]: '';
		$td = '';
		// 権限の詳細を取得して処理
		$cap = (isset($r['capabilities'])) ? $r['capabilities']: '';
		// まずはダッシュボードを処理するので配列にいれておく
		$cap = array_merge(array('dashboard'=>1), $cap);
		// 寄稿者権限相当
		if(!empty($cap['level_1'])){
			$cap = array_merge($cap, array('jetpack'=>1,'tool'=>1));
		}
		// 投稿者権限相当
		if(!empty($cap['level_3'])){
			$cap = array_merge($cap, array('option'=>1));
		}
		// 編集者権限相当
		if(!empty($cap['level_3'])){
			$cap = array_merge($cap, array('feedback'=>1));
		}
		//
		foreach($cap as $cap_key => $val){
			switch($cap_key){
				// ダッシュボード
				case 'dashboard':
					$data_name = 'ダッシュボード';
					$data_key = 'dashboard';
					break;
				// 投稿
				case 'edit_posts':
					$data_name = '投稿';
					$data_key = 'post';
					break;
				// コメント
				case 'moderate_comments':
					$data_name = 'コメント';
					$data_key = 'comment';
					break;
				// メディア
				case 'upload_files':
					$data_name = 'メディア';
					$data_key = 'media';
					break;
				// 固定ページ
				case 'edit_pages':
					$data_name = '固定ページ';
					$data_key = 'page';
					break;
				// プロフィール
				case 'read':
					$data_name = 'プロフィール';
					$data_key = 'profile';
					break;
				// 外観
				case 'switch_themes':
					$data_name = '外観';
					$data_key = 'theme';
					break;
				// プラグイン
				case 'edit_plugins':
					$data_name = 'プラグイン';
					$data_key = 'plugin';
					break;
				// ユーザ
				case 'edit_users':
					$data_name = 'ユーザ';
					$data_key = 'user';
					break;
				// ツール
				case 'tool':
					$data_name = 'ツール';
					$data_key = 'tool';
					break;
				// Jetpack
				case 'jetpack':
					$data_name = 'Jetpack';
					$data_key = 'jetpack';
					break;
				// 設定
				case 'option':
					$data_name = '設定';
					$data_key = 'option';
					break;
				// 設定ツール
				case 'feedback':
					$data_name = 'フィードバック';
					$data_key = 'feedback';
					break;
			}
			//
			if(isset($data_key)){
				$role_data = (isset($d[$data_key]) && isset($d[$data_key]['return'])) ? $d[$data_key]['return']: 0;
				$checked = ($role_data==1) ? 'checked': '';
				$td .= '<span class="rpdg15"><label for="'.$data_key.'_'.$key.'">'.$data_name.'</label><input type="checkbox" name="'.$data_key.'['.$key.']" id="'.$data_key.'_'.$key.'" value="1" '.$checked.' /></span>';
				unset($data_key);
			}
		}

		return $td;

	}

}