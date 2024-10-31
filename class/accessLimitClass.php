<?php
/*
*  ユーザ別アクセス制限Class
*/
class OswpcAccessLimit extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	// 設定を更新
	public static function update_aclimit_option($post, $options){

		if(!empty($post)){
			foreach($post as $key => $p){
				switch($key){
					case 'access_limit': case 'limit_text':
						$options[$key] = $p;
						break;
				}
			}
		}

		return update_option(OSWPC_OPTIONS, $options);

	}
	// 制限時の文章
	public static function limit_text(){

		global $oswpc_options;
		$options = $oswpc_options;
		//
		if(!empty($options['limit_text'])){
			$text = $options['limit_text'];
		}else{
			$text = '閲覧権限がないため、この記事を表示することができません。';
		}

		return $text;

	}
	/*
	*  ショートコードの処理
	*/
	public static function shortcode($atts, $content=null){

		global $oswpc_options;
		$options = $oswpc_options;
		// 有効なら実行
		if(!empty($options['access_limit'])){
			extract(shortcode_atts(array(
				'view'=>'off', 'text'=>'on', 'group'=>0, 'uid'=>0, 'user'=>'',
			), $atts));
			// 非表示時のテキスト
			if($text!='off'){
				$limit_text = self::limit_text();
			}else{
				$limit_text = '';
			}
			// 初期値
			$flag = 0;
			$user_id = 0;
			$user_role = '';
			// ログインしていれば
			if(is_user_logged_in()){
				$user_data = wp_get_current_user(); // ログインユーザデータ
				$user_id = (isset($user_data->ID)) ? $user_data->ID: 0;
				$user_login = (isset($user_data->user_login)) ? $user_data->user_login: '';
				$user_role = (isset($user_data->roles) && isset($user_data->roles[0])) ? $user_data->roles[0]: '';
			}
			// ユーザidの指定があれば
			if(!empty($uid)){
				$uid_ex = explode(',', rtrim($uid, ','));
				// 処理
				foreach($uid_ex as $u){
					if($u==$user_id){ // 一致
						$flag = 1;
						break;
					}
				}
			}
			// ログインID
			if(!empty($user)){
				$user_ex = explode(',', rtrim($user, ','));
				// 処理
				foreach($user_ex as $u){
					if($u==$user_login){ // 一致
						$flag = 1;
						break;
					}
				}
			}
			// まだフラグが0で、権限グループの指定があれば
			if(empty($flag) && !empty($group)){
				$group_ex = explode(',', rtrim($group, ','));
				// 処理
				foreach($group_ex as $g){
					if($g==$user_role){ // 一致
						$flag = 1;
						break;
					}
				}
			}
			// 表示するか否か
			switch($view){
				// フラグがなければ非表示
				case 'on':
					if(empty($flag)){
						$content = $limit_text;
					}
					break;
				// フラグがあれば非表示
				default:
					if(!empty($flag)){
						$content = $limit_text;
					}
			}
		}

		return $content;

	}
	// ショートコードの閉じタグがない場合、閉じタグを記事最後につけておく
	public static function content_close_shortcode($the_content){

		// 閉じがなければ
		if(stristr($the_content, '[accessLimit') && !stristr($the_content, '[/accessLimit]')){
			$the_content .= '[/accessLimit]';
		}

		return $the_content;

	}


}