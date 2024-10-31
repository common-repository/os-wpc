<?php
/*
*  独自ログインフォームClass
*/
class OswpcCustomLogin extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	/*
	*  ログイン処理
	*/
	// ログインチェック、処理
	public static function post_customlogin(){

		$mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode']: '';
		//
		if(!empty($_POST) && $mode=='customlogin'){
			$error = '';
			$loginId = (isset($_POST['log'])) ? $_POST['log']: '';
			$pwd = (isset($_POST['pwd'])) ? $_POST['pwd']: '';
			$login_url = (isset($_POST['login_url'])) ? $_POST['login_url']: home_url('/');
			// メールアドレスが未入力
			if(empty($loginId)){
				$error .= '&em_error=1';
			}
			// パスワードが未入力
			if(empty($pwd)){
				$error .= '&pwd_error=1';
			}
			// エラーがなければ実行
			if(empty($error)){
				// メールアドレスチェック
				if($id = email_exists($loginId)){
					// 存在する
				// ログインIDでチェック
				}elseif($user_by = get_user_by('login', $loginId)){
					$id = $user_by->ID;
				}else{ // メールアドレスorログインIDが存在しない場合
					$error .= '&not_em_error=1';
				}
				// エラーがなければ
				if(!empty($id) && empty($error)){
					$user_info = get_userdata($id);
					$hash_pwd = $user_info->user_pass;
					// パスワードをチェック
					if(wp_check_password($pwd, $hash_pwd, $id)){
						$creds = array(
							'user_login'=>$user_info->user_login, 'user_password'=>$pwd, 'remember'=>true,
						);
						$signon_data = wp_signon( $creds, false );
						// 成功ならWP_Userが返ってくる
						if(isset($signon_data->data) && !empty($signon_data->data->ID)){

						}else{ // 失敗
							$error .= '&login_error=1';
						}
					}else{ // パスワード間違い
						$error .= '&not_pwd_error=1';
					}
				}
			}
			// エラーがあれば
			if(!empty($error)){
				if(!stristr($login_url, "?")){
					$error = '?'.ltrim($error, "&");
				}
				wp_safe_redirect($login_url.$error);
				exit;
			}else{ // エラーがなければ
				$redirect_to = (!empty($_POST['redirect_to'])) ? $_POST['redirect_to']: home_url('/wp-admin/');
				wp_safe_redirect($redirect_to);
				exit;
			}

		}

	}
	// ログインエラーメッセージ
	public static function login_error_msg(){

		$error_text = '';
		//
		if(!empty($_REQUEST['em_error'])){
			$error_text .= '<p>メールアドレスまたはログインIDを入力してください。</p>';
		}
		//
		if(!empty($_REQUEST['pwd_error'])){
			$error_text .= '<p>パスワードを入力してください。</p>';
		}
		//
		if(!empty($_REQUEST['not_em_error'])){
			$error_text .= '<p>メールアドレスまたはログインIDが登録されていません。</p>';
		}
		//
		if(!empty($_REQUEST['not_pwd_error'])){
			$error_text .= '<p>パスワードが間違っています。</p>';
		}
		//
		if(!empty($_REQUEST['login_error'])){
			$error_text .= '<p>ログインに失敗しました。</p>';
		}
		//
		if(!empty($error_text)){
			echo '<div class="error-msg">'.$error_text.'</div>';
		}

	}
	/*
	*  ショートコードの処理
	*/
	public static function shortcode($atts, $content=null){

		global $oswpc_cl_options;
		extract(shortcode_atts(array(
			'type'=>'default', 'redirect'=>'',
		), $atts));
		//
		switch($type){
			// ウィジェット
			case 'wd': case 'widget':
				$options = (isset($oswpc_cl_options['widget'])) ? $oswpc_cl_options['widget']: '';
				$type = 'widget';
				break;
			// 記事内
			default:
				$options = (isset($oswpc_cl_options['page'])) ? $oswpc_cl_options['page']: '';
		}

		ob_start();
		include_once(OSWPC_INCLUDE_FILES."/public-customLogin.php");
		$form_content = ob_get_contents();
		ob_end_clean();

		return $content.$form_content;

	}
	/*
	*  プラグイン設定を更新
	*/
	public static function update_customlogin_option($post, $options=array()){

		if(!empty($post)){
			foreach($post as $key => $arr){
				switch($key){
					case '_wpnonce': case 'submit':
						break;
					case 'page': case 'widget':
						foreach($arr as $name => $p){
							$options[$key][$name] = $p;
						}
						break;
				}
			}
		}

		return update_option(OSWPC_CL_OPTIONS, $options);

	}

}