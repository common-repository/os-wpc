<?php
/*
*  その他カスタマイズClass
*/
class OswpcEtc extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	// カスタマイズを実行
	public static function etc_customize(){

		global $oswpc_options;
		if(!empty($oswpc_options)){
			$options = $oswpc_options;
		}else{
			$options = get_option(OSWPC_OPTIONS);
		}
		// 自動改行を削除
		if(!empty($options['remove_wpautop'])){
			remove_filter('the_content', 'wpautop');
			remove_filter('the_excerpt', 'wpautop');
		}
		// アイキャッチ画像
		if(!empty($options['post_thumbnails'])){
			$post_thumbnails_array = array();
			//
			foreach($options['post_thumbnails'] as $key => $op){
				// 有効
				if(!empty($op)){
					$post_thumbnails_array = array_merge($post_thumbnails_array, array($key));
				}
			}
			// 値があれば実行
			if(!empty($post_thumbnails_array)){
				add_theme_support('post-thumbnails', $post_thumbnails_array);
			}
		}
		// HTML5マークアップ
		if(!empty($options['html5'])){
			$markup_array = array();
			//
			foreach($options['html5'] as $key => $op){
				// 有効
				if(!empty($op)){
					$markup_array = array_merge($markup_array, array($key));
				}
			}
			// 値があれば実行
			if(!empty($markup_array)){
				add_theme_support('html5', $markup_array);
			}
		}
		// タイトルタグ
		if(!empty($options['title_tag'])){
			add_theme_support('title-tag');
		}
		// フィードリンク
		if(!empty($options['feed_links'])){
			add_theme_support('automatic-feed-links');
		}
		// ウィジェット内のショートコード
		if(!empty($options['widget_shortcode'])){
			add_filter('widget_text', 'do_shortcode');
		}
		// ナビゲーションメニュー
		if(!empty($options['menus'])){
			add_theme_support('menus');
		}
		// バージョン情報を非表示
		if(!empty($options['not_public_wp_version'])){
			remove_action('wp_head', 'wp_generator');
			$feed_head = array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head');
			foreach($feed_head as $arr){
				remove_action($arr, 'the_generator');
			}
		}

	}
	// カスタマイズを実行（管理画面のみでの実行）
	public static function admin_etc_customize(){

		global $oswpc_options;
		if(!empty($oswpc_options)){
			$options = $oswpc_options;
		}else{
			$options = get_option(OSWPC_OPTIONS);
		}
		// 管理画面上のバージョンアップ通知
		if(!empty($options['not_version_up'])){
			switch($options['not_version_up']){
				case 2: case '2':
					if(!current_user_can('administrator')){
						remove_action('admin_notices', 'update_nag', 3);
					}
					break;
				default:
					remove_action('admin_notices', 'update_nag', 3);
			}
		}

	}
	// カスタマイズ、管理画面下のバージョン情報を非表示
	public static function custom_update_footer($text){

		global $oswpc_options;
		if(!empty($oswpc_options)){
			$options = $oswpc_options;
		}else{
			$options = get_option(OSWPC_OPTIONS);
		}
		// 実行
		if(!empty($options['not_footer_version'])){
			return '';
		}else{ // 何もしない
			return $text;
		}

	}
	// カスタマイズ、管理画面フッターのWP文言を変更
	public static function custom_admin_footer_text($text){

		global $oswpc_options;
		if(!empty($oswpc_options)){
			$options = $oswpc_options;
		}else{
			$options = get_option(OSWPC_OPTIONS);
		}
		// 実行
		if(!empty($options['admin_thanks_text_flag'])){
			return (isset($options['admin_thanks_text'])) ? $options['admin_thanks_text']: '';
		}else{ // 何もしない
			return $text;
		}

	}
	// 設定を更新
	public static function update_etc_option($post, $options){

		if(!empty($post)){
			// あらかじめクリアしておく
			$options['remove_wpautop'] = 0;
			$options['post_thumbnails'] = array();
			$options['html5'] = array();
			$options['title_tag'] = 0;
			$options['feed_links'] = 0;
			$options['widget_shortcode'] = 0;
			$options['menus'] = 0;
			$options['not_public_wp_version'] = 0;
			$options['not_version_up'] = 0;
			$options['not_footer_version'] = 0;
			$options['admin_thanks_text_flag'] = 0;
			$options['admin_thanks_text'] = '';
			// 上書き処理
			foreach($post as $key => $p){
				switch($key){
					case 'remove_wpautop': case 'post_thumbnails': case 'html5': case 'title_tag': case 'feed_links': case 'widget_shortcode': case 'menus': case 'not_public_wp_version': case 'not_version_up': case 'not_footer_version': case 'admin_thanks_text_flag': case 'admin_thanks_text':
						$options[$key] = $p;
						break;
				}
			}
		}

		return update_option(OSWPC_OPTIONS, $options);

	}
	// 投稿タイプを取得
	public static function get_posttype(){

		$return = array('post'=>'投稿', 'page'=>'固定');
		$args = array(
			'public'=>true, '_builtin'=>false
		);
		$post_types = get_post_types( $args );
		//
		if(!empty($post_types)){
			foreach($post_types as $post_type){
				$label = get_post_type_object($post_type)->label;
				$return[$post_type] = $label;
			}
		}

		return $return;

	}
	// HTML5マークアップの対象
	public static function html5_markup_target(){

		return array('comment-list'=>'コメントリスト', 'comment-form'=>'コメントフォーム', 'search-form'=>'検索フォーム', 'gallery'=>'ギャラリー', 'caption'=>'キャプション');

	}


}