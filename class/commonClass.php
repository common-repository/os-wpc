<?php
$oswpc_options = ''; // オプション格納用
$oswpc_cl_options = ''; // ログインフォーム用オプション
/*
*  共通Class
*/
class OswpcCommonClass{

	public function __construct(){
		add_action('init', array('OswpcCommonClass', 'get_wpc_option'));
	}
	// オプションデータを取得
	public static function get_wpc_option(){

		// オプションを取得し、グローバル変数へ
		$GLOBALS['oswpc_options'] = get_option(OSWPC_OPTIONS);
		$GLOBALS['oswpc_cl_options'] = get_option(OSWPC_CL_OPTIONS);

	}
	/*
	*  ショートコードがあるか否か
	*/
	public static function has_shortcode($shortcode){

		global $wp_query;
		// 記事データを取得
		if(isset($wp_query->post)){
			$post = $wp_query->post;
			if(isset($post->post_content)){
				$post_data = $post->post_content;
			}
		}
		//　取得できなければ別の配列からもう一度試みる
		if(isset($wp_query->posts) && !isset($post_data)){
			$posts = $wp_query->posts;
			if(isset($posts[0]) && isset($posts[0]->post_content)){
				$post_data = $posts[0]->post_content;
			}
		}
		// 投稿データにショートコードが含まれるか
		if(isset($post_data)){
			// 含めばTRUE
			if(stristr($post_data, "[".$shortcode)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}

	}
	/*
	*  独自リダイレクト（先にヘッダが送信されているとリダイレクトできないため）
	*/
	public static function os_redirect($url=''){

		if(self::header_check()==TRUE){
			print '<meta http-equiv="refresh" content="0;URL='.$url.'" />';
		}else{
			wp_safe_redirect($url);
		}

	}
	// ヘッダーが送信されているかチェック
	public static function header_check(){

		if(headers_sent($filename, $linenum)){
			//print_r(headers_list());
			//echo "$filename の $linenum 行目でヘッダがすでに送信されています。\n";
			return TRUE;
		}else{
			return FALSE;
		}

	}
	// 処理中の表示
	public static function now_processing(){
		echo "<div style=\"margin:20px 10px 0 0;\"><h2>処理中です…</h2></div>";
		exit;
	}
	/*
	*  ページ数、件数
	*/
	public static function page_count($page='', $limit='20'){

		// ページ数取得
		if(empty($page)){
			$page = (!empty($_REQUEST['pnum']) && is_numeric($_REQUEST['pnum'])) ? $_REQUEST['pnum']: 1;
		}
		//
		$end = $page * $limit;
		$start = ($end - $limit) + 1;

		return array('start'=>$start, 'end'=>$end, 'page'=>$page);

	}
	/*
	*  $_REQUESTをURL用に変換
	*/
	public static function request_url($url='', $unset_param=array()){

		if(!empty($_REQUEST)){
			if(stristr($url, "?")){
				$url .= "&amp;";
			}else{
				$url .= "?";
			}
			// まずはアンセット処理
			if(!empty($unset_param)){
				foreach($unset_param as $uns){
					unset($_REQUEST[$uns]);
				}
			}
			//
			foreach($_REQUEST as $key => $req){
				$url .= urlencode($key)."=".urlencode($req)."&amp;";
			}
			$url = rtrim($url, "&amp;");
		}

		return $url;

	}
	// 念のためPHPバージョン確認をして実行、なければエラーを返す
	public static function phpversion_check($version=5, $str=''){

		if(phpversion()>=$version){
			return TRUE;
		}else{
			print $str."PHPバージョン".$version."以上が必要です。";
			exit;
		}

	}

}