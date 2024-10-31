<?php
// バリデーションclass
class OswpcValidationClass extends OswpcCommonClass {

	public function __construct(){

		parent::__construct();

	}
	/*
	*  バリデーションのエラーがあるかどうか
	*/
	public static function validates($validation=''){

		$return_data = true;

		if(!empty($validation)){
			foreach($validation as $val){
				if(!empty($val)){
					$return_data = false;
					break;
				}
			}
		}

		return $return_data;

	}
	/*
	*  バリデーションメッセージの修正
	*/
	public static function validates_message($validation='', $array=array()){

		foreach($validation as $vkey => $valid){
			if(!empty($valid['error'])){
				foreach($array as $key => $arr){
					if(stristr($valid['text'], $key)){
						$validation[$vkey]['text'] = str_replace($key, $arr, $valid['text']);
					}
				}
			}
		}

		return $validation;

	}
	/*
	*  バリデーションルール
	*/
	public static function validation_rule($post='', $key='', $rule='', $ids=''){

		$validation_data = array();
		$rule_arr = array();
		$str1 = '';
		$str2 = '';

		if(is_array($rule)){ // 配列ならそのまま
			$rule_arr = $rule;
		}else{
			$rule_arr = array($rule);
		}
		// チェック
		foreach($rule_arr as $r){
			if(is_array($r)){ // 更に配列なら
				$first = current($r); // 1番目の値
				//
				if(!empty($first)){
					// 2番目の値
					if($second = next($r)){
						$str1 = $second;
					}else{
						$second = 0;
					}
					// 末尾の値
					if($end = end($r)){
						$str2 = $end;
					}else{
						$end = 0;
					}
					//
					switch($first){
						case 'number':
							$return_rule = self::validation_number($post, $second, $end);
							break;
					}
					$rkey = $first;
				}
			}else{
				switch($r){
					// 空ならエラー
					case 'empty': case 'select-empty':
						$return_rule = self::validation_empty($post);
						break;
					// 0もしくは何か文字列があるかどうか
					case 'z-empty':
						$return_rule = self::validation_zero_or_empty($post);
						break;
					// 数字かどうか
					case 'numeric':
						$return_rule = self::validation_numeric($post);
						break;
					// 英数字かどうか
					case 'eng_numeric':
						$return_rule = self::validation_eng_numeric($post);
						break;
					// 英数字とアンダーバーかどうか
					case 'eng_und_numeric':
						$return_rule = self::validation_eng_und_numeric($post);
						break;
					// 英数字と半角記号かどうか
					case 'eng_symbol_numeric':
						$return_rule = self::validation_eng_symbol_numeric($post);
						break;
					// スペースを含むかどうか。あるなら拒否
					case 'is_n_space':
						$return_rule = self::validation_is_n_space($post);
						break;
					// パーセントを含むかどうか。あるなら拒否
					case 'is_n_per':
						$return_rule = self::validation_is_n_per($post);
						break;
					// 2つの値が一致するかどうか
					case 'is_equal':
						$return_rule = self::validation_is_equal($post, $key);
						break;
				}
				$str1 = '';
				$str2 = '';
				$rkey = $r;
			}
			// エラーなら
			if($return_rule==1){
				$validation_data[$key]['error'] = $return_rule;
				$validation_data[$key]['rule'] = $rkey;
				$validation_data[$key]['id'] = $ids;
				$validation_data[$key]['text'] = self::validation_error_message($key, $rkey, $str1, $str2);
			}
			unset($return_rule);
		}

		return $validation_data;

	}
	// 空かどうか
	public static function validation_empty($post=''){

		if(empty($post)){ // 空なら問題あり
			return 1;
		}else{
			return 0;
		}

	}
	// 0か空かどうか
	public static function validation_zero_or_empty($post=''){

		if(empty($post)){ // 空なら
			if(is_numeric($post)){ // 0なら
				return 0;
			}else{ // 0でなければ問題あり
				return 1;
			}
		}else{
			return 0;
		}

	}
	// 数字かどうか
	public static function validation_numeric($post=''){

		if(is_numeric($post)){ // 数値なら問題なし
			return 0;
		}else{
			return 1;
		}

	}
	// 英数字かどうか
	public static function validation_eng_numeric($post=''){

		if(preg_match('/^([a-zA-Z0-9]+)$/u', $post, $matches)){
			return 0;
		}else{
			return 1;
		}

	}
	// 英数字とアンダーバーかどうか
	public static function validation_eng_und_numeric($post=''){

		if(preg_match('/^([a-zA-Z0-9_]+)$/u', $post, $matches)){
			return 0;
		}else{
			return 1;
		}

	}
	// 英数字と記号かどうか
	public static function validation_eng_symbol_numeric($post=''){

		if(preg_match('/^([a-zA-Z0-9 -\/:-@\[-`\{-\~]+)$/u', $post, $matches)){
			return 0;
		}else{
			return 1;
		}

	}
	// スペースを含むかどうか。スペースなら拒否
	public static function validation_is_n_space($post=''){

		// 全角スペースなら半角にする
		$post = str_replace('　', ' ', $post);
		//
		if(preg_match('/[\s]/u', $post, $matches)){
			return 1;
		}else{
			return 0;
		}

	}
	// パーセントを含むかどうか。パーセントなら拒否
	public static function validation_is_n_per($post=''){

		// 全角スペースなら半角にする
		$post = str_replace('％', '%', $post);
		//
		if(preg_match('/[%]/u', $post, $matches)){
			return 1;
		}else{
			return 0;
		}

	}
	// 2つの値が一致するかどうか
	public static function validation_is_equal($post='', $key=''){

		$check_data = '';
		$key_name = 're_'.$key;
		//
		if(isset($_POST[$key_name])){
			$check_data = $_POST[$key_name];
		}else{
			if(isset($_REQUEST[$key_name])){
				$check_data = $_REQUEST[$key_name];
			}
		}
		//
		if($post==$check_data){
			return 1;
		}else{
			return 0;
		}

	}
	// 指定した文字数内か
	public static function validation_number($post='', $start='', $end=''){

		$start_error = 0;
		$end_error = 0;
		$count = mb_strlen($post, "UTF-8");
		// 開始文字数
		if(!empty($start)){
			if($start<$count || $count==$start){
				$start_error = 0;
			}else{
				$start_error = 1;
			}
		}
		// 終了文字数
		if(!empty($end)){
			if($count<$end || $count==$end){
				$end_error = 0;
			}else{
				$end_error = 1;
			}
		}
		//
		if(!empty($end_error) || !empty($start_error)){ // どちらかが該当すれば
			return 1;
		}else{
			return 0;
		}

	}
	/*
	*  エラーメッセージ
	*/
	public static function validation_error_message($key='', $rule='', $str1='', $str2=''){

		switch($rule){
			case 'empty':
				$message = $key.'は必須入力です。';
				break;
			case 'z-empty':
				$message = $key.'は必須入力です。';
				break;
			case 'select-empty':
				$message = $key.'を選択してください。';
				break;
			case 'numeric':
				$message = $key.'は半角数字のみ入力可能です。';
				break;
			case 'eng_numeric':
				$message = $key.'は半角英数字のみ入力可能です。';
				break;
			case 'eng_und_numeric':
				$message = $key.'は半角英数字とアンダーバーのみ入力可能です。';
				break;
			case 'eng_symbol_numeric':
				$message = $key.'は半角英数字と半角記号が入力可能です。';
				break;
			case 'is_n_space':
				$message = $key.'にスペース（空白）は使用できません。';
				break;
			case 'is_n_per':
				$message = $key.'にパーセント（%）は使用できません。';
				break;
			case 'is_equal':
				$message = $key.'の値が一致しません。';
				break;
			case 'number':
				$text = '';
				if(!empty($str1)){
					$text .= $str1.'文字から';
				}
				if(!empty($str2)){
					$text .= $str2.'文字まで';
				}
				$message = $key.'は'.$text.'入力可能です。';
				break;
			default:
				$message = '';
		}

		return $message;

	}
	/*
	*  バリデーションエラーを出力
	*/
	// エラー文を出力
	public static function output_msg($validate='', $type=''){

		$message = '';
		//
		if(!empty($validate)){
			foreach($validate as $val){
				$message .= '<p>'.esc_html($val['text']).'</p>';
			}
		}
		// メッセージがあれば
		if(!empty($message)){
			switch($type){
				case 1:
					return $message;
					break;
				default:
					echo $message;
			}
		}else{
			return false;
		}

	}
	// エラー用のスタイルシートを出力
	public static function output_css($validate='', $type=''){

		$message = '';
		//
		if(!empty($validate)){
			foreach($validate as $key => $val){
				$message .= '#'.esc_html($key).'{border:1px solid red;} ';
			}
		}
		// メッセージがあれば
		if(!empty($message)){
			switch($type){
				case 1:
					return $message;
					break;
				default:
					echo "<style type=\"text/css\">\n";
					echo $message;
					echo "\n</style>";
			}
		}else{
			return false;
		}

	}
	// エラーがある場合、編集画面は開いたままにしておく
	public static function output_jquery($validate='', $keyname='', $type=''){

		$message = '';
		//
		if(!empty($validate)){
			foreach($validate as $key => $val){
				$i = (isset($val['id'])) ? $val['id']: '';
				$r_name = $keyname.$i;
				$message .= "\t"."if(jQuery('#".$r_name." .error-open').length){\n";
				$message .= "\t\t"."jQuery('#".$r_name." .error-open').show();\n";
				$message .= "\t"."}\n";
				$message .= "\t"."if(jQuery('#".$r_name." .error-close').length){\n";
				$message .= "\t\t"."jQuery('#".$r_name." .error-close').hide();\n";
				$message .= "\t"."}\n";
			}
		}
		// メッセージがあれば
		if(!empty($message)){
			switch($type){
				case 1:
					return $message;
					break;
				default:
					echo "<script>\n";
					echo "jQuery(document).ready(function(){\n";
					echo $message;
					echo "});\n";
					echo "</script>\n";
			}
		}else{
			return false;
		}

	}

}
?>