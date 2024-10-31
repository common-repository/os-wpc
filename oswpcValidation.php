<?php
//
class OswpcValidation extends OswpcValidationClass {

	public function __construct(){
		parent::__construct();
	}
	/*
	*  管理画面でのバリデーションチェック
	*/
	// 権限追加でのバリデーション
	public static function validation_addrole($post=''){

		$validate = array();
		//
		foreach($post as $key => $p){
			switch($key){
				case 'role': case 'capabilities':
					$this_validate = self::validation_rule($p, $key, array('empty', 'eng_numeric', 'is_n_space', array('number', 0, 80)));
					break;
				case 'name':
					$this_validate = self::validation_rule($p, $key, array('empty', array('number', 0, 100)));
					break;
				default:
					$this_validate = '';
			}
			if(!empty($this_validate)){
				// 結合
				$validate = array_merge($validate, $this_validate);
			}
		}
		// エラーがあれば
		if(!empty($validate)){
			// メッセージを修正
			$change_arr = array(
				'role'=>'権限グループ名', 'name'=>'表示名', 'capabilities'=>'コピー元'
			);
			$validate = self::validates_message($validate, $change_arr);
		}

		return $validate;

	}


}
?>