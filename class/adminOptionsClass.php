<?php
/*
*  プラグイン設定Class
*/
class OswpcAdminOptions extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	// プラグイン設定を更新
	public static function update_oswpc_option($post, $options){

		if(!empty($post)){
			foreach($post as $key => $p){
				switch($key){
					case 'edit_oswpc':
						$options[$key] = array(); // 初期化
						//
						foreach($p as $rol => $val){
							$options[$key][$rol] = $val;
						}
						break;
				}
			}
			// どれもチェックマークされていないとき
			if(empty($post['edit_oswpc'])){
				$options['edit_oswpc'] = array();
			}
		}

		return update_option(OSWPC_OPTIONS, $options);

	}

}