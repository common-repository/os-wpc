<?php
include_once(OSWPC_PLUGIN_DIR."class/adminMenuClass.php"); // 管理画面の制限class
include_once(OSWPC_PLUGIN_DIR."class/roleClass.php"); // ユーザ権限追加、編集class
include_once(OSWPC_PLUGIN_DIR."class/adminOptionsClass.php"); // プラグイン設定class
include_once(OSWPC_PLUGIN_DIR."class/accessLimitClass.php"); // ユーザ別アクセス制限class
include_once(OSWPC_PLUGIN_DIR."class/customLoginClass.php"); // 独自ログインclass
include_once(OSWPC_PLUGIN_DIR."class/etcClass.php"); // その他class
/*
*  管理画面class
*/
class OswpcAdmin extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();
		// 権限グループ名の表示変更
		add_action('init', array('OswpcRole', 'change_role_name'));
		// 管理画面で実行
		add_action('admin_init', array('OswpcAdmin', 'actionAdminInit'));
		// 管理画面メニュー
		add_action('admin_menu', array('OswpcAdmin', 'menuViews'));
		add_action('admin_footer', array('OswpcAdmin', 'change_highlight_menu_item'));
		//add_action('init', array('OswpcAdmin', 'codex_custom_init'));
		// カスタマイズ実行
		add_action('admin_init', array('OswpcEtc', 'admin_etc_customize'));
		add_filter('update_footer', array('OswpcEtc', 'custom_update_footer'), 99);
		add_filter('admin_footer_text', array('OswpcEtc', 'custom_admin_footer_text'), 99);
		
	}
	// カスタム投稿タイプ(テスト用)
	public function codex_custom_init(){
		//
		register_post_type(
			'it',
			array(
				'menu_position' => 5,
				'labels' => array(
					'name' => 'テスト用カスタム投稿',
					'add_new_item' => 'テスト用カスタム投稿を追加',
					'edit_item' => 'テスト用カスタム投稿の編集',
				),
				'public' => true, 'rewrite' => true, 'show_in_nav_menus' => true,
				'supports' => array(
					'title', 'editor', 'custom-fields', 'thumbnail',
				),
			)
		);
		register_taxonomy_for_object_type('category', 'it');

	}
	// Feedの修正
	public function custom_post_rss_set($query){

		if(is_feed()){
			$post_type = $query->get('post_type');
			//
			if(empty($post_type)){
				$query->set('post_type',
					array('post', 'zi10', 'it', 'maker')
				);
			}
		}

		return $query;

	}
	/*
	*  プラグインメニュー
	*/
	// メニュー表示
	public static function menuViews(){

		$role = 'administrator';
		global $oswpc_options;
		$options = $oswpc_options;
		$edit_role = (isset($options['edit_oswpc'])) ? $options['edit_oswpc']: '';
		$user = wp_get_current_user(); // ログインユーザデータ
		$user_role = (isset($user->roles) && isset($user->roles[0])) ? $user->roles[0]: 'guest';
		// ユーザ権限があれば
		if(is_array($edit_role) && array_key_exists($user_role, $edit_role)){
			$role = $user_role; // 置き換え
		}
		// メニュー表示
		add_menu_page('OS-WPカスタマイズプラグイン', 'OS-WPカスタマイズプラグイン', $role, 'os-wpc-admin.php', array('OswpcAdmin', 'adminPage'));
		// サブメニュー
		add_submenu_page('os-wpc-admin.php', '管理画面の制限', '<span id="os-wpc-admin_menu">管理画面の制限</span>', $role, 'admin.php?page=os-wpc-admin.php&amp;mode=admin_menu');
		add_submenu_page('os-wpc-admin.php', '権限の追加・編集', '<span id="os-wpc-role_edit">権限の追加・編集</span>', $role, 'admin.php?page=os-wpc-admin.php&amp;mode=role_edit');
		add_submenu_page('os-wpc-admin.php', 'ページ表示制限のショートコード', 'ページ表示制限のショートコード', $role, 'os-wpc-accesslimit.php', array('OswpcAdmin', 'adminAccessLimitPage'));
		add_submenu_page('os-wpc-admin.php', '独自ログインフォーム', '独自ログインフォーム', $role, 'os-wpc-customlogin.php', array('OswpcAdmin', 'adminCustomLoginPage'));
		add_submenu_page('os-wpc-admin.php', 'その他カスタマイズ', 'その他カスタマイズ', $role, 'os-wpc-etc.php', array('OswpcAdmin', 'adminEtcPage'));
		add_submenu_page('os-wpc-admin.php', 'プラグインの設定', 'プラグインの設定', $role, 'os-wpc-option.php', array('OswpcAdmin', 'adminOptionPage'));
		// サブメニューの表示を修正
		global $submenu;
		if(isset($submenu) && isset($submenu['os-wpc-admin.php'])){
			$sub = $submenu['os-wpc-admin.php'];
			// プラグインのトップページの文言変更
			if(isset($sub[0]) && isset($sub[0][0])){
				$sub[0][0] = 'はじめに';
			}
			// 置き換え
			$submenu['os-wpc-admin.php'] = $sub;
		}

	}
	/*
	*  ページビュー
	*/
	// Page はじめに
	public static function adminPage(){

		$mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode']: '';
		$user = wp_get_current_user(); // ログインユーザデータ
		$my_id = (isset($user->ID)) ? $user->ID: 0;
		$action = $mode.'-'.$myid;
		//
		switch($mode){
			// 管理画面の制限
			case 'admin_menu':
				self::modeAdminMenu($mode, $action);
				break;
			// 権限の追加・編集
			case 'role_edit':
				self::modeRoleEdit($mode, $action);
				break;
			// 利用規約
			case 'agreement':
				include_once(OSWPC_INCLUDE_FILES."/admin-agreementPage.php");
				break;
			// はじめに
			default:
				include_once(OSWPC_INCLUDE_FILES."/admin-adminPage.php");
		}

	}
	// Page => mode
	// 管理画面の制限
	public static function modeAdminMenu($mode, $action){

		$roles = self::get_roles();
		// POST時
		if(!empty($_POST)){
			// nonceの認証成功
			if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $action)){
				$return_id = OswpcSql::insert_admin_menu_data($roles);
				// 成功
				if($return_id!==FALSE){
					self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=admin_menu&amp;msg=update-ok');
				}else{ // 失敗
					self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=admin_menu&amp;msg=update-ng');
				}
			}else{ // 認証失敗
				self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=admin_menu&amp;msg=nonce-ng');
			}
		}
		//
		$message = OswpcMessageClass::updateMessage();
		$data = OswpcSql::get_admin_menu();
		$myClass = new OswpcAdminMenu();
		include_once(OSWPC_INCLUDE_FILES."/admin-modeAdminMenuPage.php");

	}
	// Page => mode
	// 権限の追加・編集
	public static function modeRoleEdit($mode, $action){

		$roles = self::get_roles();
		$edit_mode = (isset($_REQUEST['edit_mode'])) ? $_REQUEST['edit_mode']: '';
		$message = OswpcMessageClass::updateMessage();
		$message2 = OswpcMessageClass::updateMessage2();
		$amClass = new OswpcAdminMenu();
		$myClass = new OswpcRole();
		// POST時
		if(!empty($_POST)){
			// nonceの認証成功
			if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $action)){
				switch($edit_mode){
					// 編集
					case 'edit':
						// 実行（DB）
						if($myClass->edit_role($roles)!==FALSE){ // 成功なら
							self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=role_edit&amp;msg=update-ok');
						}else{ // 失敗
							self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=role_edit&amp;msg=update-ng');
						}
						break;
					// 追加
					case 'add':
						$validate = OswpcValidation::validation_addrole($_POST);
						// バリデーションチェックOK
						if($result = OswpcValidation::validates($validate)){
							// 新規作成を実行
							if($myClass->new_role()===TRUE){ // 成功なら
								self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=role_edit&amp;msg2=insert-ok#roleAdd');
							}else{ // 失敗
								self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=role_edit&amp;msg2=insert-ng#roleAdd');
							}
						}
						break;
					// 削除
					case 'delete':
						$myClass->delete_role();
						self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=role_edit&amp;msg=delete-ok');
						break;
					default:
				}
			}else{ // 認証失敗
				self::os_redirect('admin.php?page=os-wpc-admin.php&amp;mode=role_edit&amp;msg=nonce-ng');
			}
		}
		//
		include_once(OSWPC_INCLUDE_FILES."/admin-modeRoleEditPage.php");

	}
	// Page ページ表示制限ショートコード
	public static function adminAccessLimitPage(){

		global $oswpc_options;
		$options = $oswpc_options;
		$user = wp_get_current_user(); // ログインユーザデータ
		$my_id = (isset($user->ID)) ? $user->ID: 0;
		$action = 'wpc-access-limit-'.$myid;
		$myClass = new OswpcAccessLimit();
		$message = OswpcMessageClass::updateMessage();
		// POST時
		if(!empty($_POST)){
			// nonceの認証成功
			if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $action)){
				if($myClass->update_aclimit_option($_POST, $oswpc_options)!==FALSE){ // 成功なら
					self::os_redirect('admin.php?page=os-wpc-accesslimit.php&amp;msg=update-ok');
				}else{ // 失敗なら
					self::os_redirect('admin.php?page=os-wpc-accesslimit.php&amp;msg=update-op-ng');
				}
			}
		}
		//
		include_once(OSWPC_INCLUDE_FILES."/admin-adminAccessLimitPage.php");

	}
	// Page 独自ログインフォーム
	public static function adminCustomLoginPage(){

		global $oswpc_cl_options;
		$options = $oswpc_cl_options;
		$user = wp_get_current_user(); // ログインユーザデータ
		$my_id = (isset($user->ID)) ? $user->ID: 0;
		$action = 'wpc-customlogin-'.$myid;
		$myClass = new OswpcCustomLogin();
		$message = OswpcMessageClass::updateMessage();
		// POST時
		if(!empty($_POST)){
			// nonceの認証成功
			if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $action)){
				if($myClass->update_customlogin_option($_POST)!==FALSE){ // 成功なら
					self::os_redirect('admin.php?page=os-wpc-customlogin.php&amp;msg=update-ok');
				}else{ // 失敗なら
					self::os_redirect('admin.php?page=os-wpc-customlogin.php&amp;msg=update-op-ng');
				}
				exit;
			}
		}
		include_once(OSWPC_INCLUDE_FILES."/admin-adminCustomLoginPage.php");

	}
	// Page その他カスタマイズ
	public static function adminEtcPage(){

		global $oswpc_options;
		$options = $oswpc_options;
		$user = wp_get_current_user(); // ログインユーザデータ
		$my_id = (isset($user->ID)) ? $user->ID: 0;
		$action = 'wpc-access-limit-'.$myid;
		$myClass = new OswpcEtc();
		$post_types = $myClass->get_posttype();
		$markup_target = $myClass->html5_markup_target();
		$message = OswpcMessageClass::updateMessage();
		// POST時
		if(!empty($_POST)){
			// nonceの認証成功
			if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $action)){
				if($myClass->update_etc_option($_POST, $oswpc_options)!==FALSE){ // 成功なら
					self::os_redirect('admin.php?page=os-wpc-etc.php&amp;msg=update-ok');
				}else{ // 失敗なら
					self::os_redirect('admin.php?page=os-wpc-etc.php&amp;msg=update-op-ng');
				}
			}
		}
		//
		include_once(OSWPC_INCLUDE_FILES."/admin-adminEtcPage.php");

	}
	// Page プラグイン設定
	public static function adminOptionPage(){

		global $oswpc_options;
		$roles = self::get_roles();
		$role_names = (isset($roles->role_names)) ? $roles->role_names: array();
		$user = wp_get_current_user(); // ログインユーザデータ
		$my_id = (isset($user->ID)) ? $user->ID: 0;
		$action = 'wpc-options-'.$myid;
		$myClass = new OswpcAdminOptions();
		$message = OswpcMessageClass::updateMessage();
		// POST時
		if(!empty($_POST)){
			// nonceの認証成功
			if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $action)){
				if($myClass->update_oswpc_option($_POST, $oswpc_options)!==FALSE){ // 成功なら
					self::os_redirect('admin.php?page=os-wpc-option.php&amp;msg=update-ok');
				}else{ // 失敗なら
					self::os_redirect('admin.php?page=os-wpc-option.php&amp;msg=update-op-ng');
				}
			}
		}
		//
		include_once(OSWPC_INCLUDE_FILES."/admin-adminOptionPage.php");

	}
	// 権限取得
	public static function get_roles(){

		global $wp_roles;
		//
		if(!isset($wp_roles)){
			$wp_roles = new WP_Roles();
		}
		return $wp_roles;

	}
	/*
	*  管理画面フッターで実行
	*/
	// ハイライトするメニューの変更
	public static function change_highlight_menu_item(){

		if(isset($_GET['page']) && $_GET['page']=='os-wpc-admin.php'){
			$mode = (isset($_GET['mode'])) ? $_GET['mode']: '';
			// モードによる
			switch($mode){
				case 'admin_menu': case 'role_edit':
?>
					<script type="text/javascript">
					jQuery(document).ready( function($){
						var reference = $('#os-wpc-<?php echo esc_html($mode); ?>').parent().parent();
						reference.parent().find('li').removeClass('current');
						reference.addClass('current');
					});
					</script>
<?php
					break;
			}
		}
	}
	/*
	*  メニューを呼び出す前に実行する。JSやcssの設定等
	*/
	public static function actionAdminInit(){

		// 管理画面制限を実行
		OswpcAdminMenu::role_check_view();
		//
		$page = (isset($_REQUEST['page']) && !is_array($_REQUEST['page'])) ? $_REQUEST['page']: '';
		$mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode']: '';
		// os-mynumberを含むなら実行
		if(stristr($page, 'os-wpc-')){
			switch($mode){
				// 通常
				default:
					// jQuery
					wp_enqueue_script('jquery');
					// Javascript
					$dir_ex = explode("/", rtrim(OSWPC_PLUGIN_DIR, "/")); // 現在のプラグインのパス
					$now_plugin = end($dir_ex); // 現在のプラグインのディレクトリ名
					// オリジナルjs
					wp_enqueue_script('oswpc-j', plugins_url($now_plugin).'/js/j.js', array(), '1.0');
					// css
					wp_enqueue_style('oswpc-style-admin', plugins_url($now_plugin).'/style-admin.css', array(), '1.0');
			}
		}

	}

}
?>