<?php
if(class_exists('OswpcPublic')){
	$redirect_to = (isset($options['redirect_to'])) ? $options['redirect_to']: '';
	$redirect_url = '';
	//
	if(!empty($redirect)){
		$redirect_url = $redirect;
	}else{
		switch($redirect_to){
			case 'referer':
				$redirect_url = $_SERVER["HTTP_REFERER"];
				break;
			case 'now':
				$redirect_url = get_the_permalink();
				break;
			case 'top':
				$redirect_url = home_url('/');
				break;
			case 'admin':
				$redirect_url = home_url('/wp-admin/');
				break;
		}
	}
	//
	if(is_user_logged_in()):
		$logged = (isset($options['logged'])) ? $options['logged']: '';
		//
		switch($logged){
			case 'textarea':
				$logged_text = (isset($options['logged_textarea'])) ? $options['logged_textarea']: '';
				echo str_replace(array('\"', "\'"), array('"', "'"), $logged_text);
				break;
		}
?>

<?php
	else:
?>

	<form action="" id="<?php if($type=='widget'){ echo "customlogin-widget-form"; }else{ echo "customlogin-form"; } ?>" class="customlogin-form" method="POST">
		<?php echo OswpcCustomLogin::login_error_msg()."\n"; ?>
		<input type="text" name="log" value="" placeholder="メールアドレス or ログインID"/>
		<input type="password" name="pwd" value="" placeholder="パスワード" />
		<button name="submit">ログイン</button>
		<input type="hidden" name="mode" value="customlogin" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_html($redirect_url); ?>" />
		<input type="hidden" name="login_url" value="<?php echo esc_html(get_the_permalink()); ?>" />
	</form>

<?php
	endif;
}
?>