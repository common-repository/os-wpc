<?php
//
class OswpcPublic extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();
		// カスタマイズ実行
		add_action('init', array('OswpcEtc', 'etc_customize'));
		/* アクセス制限 */
		// ショートコードの存在をチェック
		add_filter('the_content', array('OswpcAccessLimit', 'content_close_shortcode'));
		add_filter('get_the_content', array('OswpcAccessLimit', 'content_close_shortcode'));
		// ショートコード
		add_shortcode('accessLimit', array('OswpcAccessLimit', 'shortcode'));
		/* 独自ログイン */
		// ログイン実行
		add_action('init', array('OswpcCustomLogin', 'post_customlogin'));
		// ショートコード
		add_shortcode('customLogin', array('OswpcCustomLogin', 'shortcode'));

	}


}
?>