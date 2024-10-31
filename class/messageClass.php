<?php
// メッセージを操作するclass
class OswpcMessageClass {

	public static function updateMessage($type=''){

		$return_data = '';

		if(isset($_GET['msg'])){
			$message = explode(",", rtrim($_GET['msg'], ","));
			//
			foreach($message as $m){
				if($type==1){
					$return_data .= self::_viewUpdateMessage($m);
				}else{
					$return_data .= self::_updateMessage($m);
				}
			}
		}

		return $return_data;

	}
	public static function updateMessage2($type=''){

		$return_data = '';

		if(isset($_GET['msg2'])){
			$message = explode(",", rtrim($_GET['msg2'], ","));
			//
			foreach($message as $m){
				if($type==1){
					$return_data .= self::_viewUpdateMessage($m);
				}else{
					$return_data .= self::_updateMessage($m);
				}
			}
		}

		return $return_data;

	}
	// メッセージ
	public static function _updateMessage($msg=''){

		$return_data = '';

		switch($msg){
			case "ok":
				$return_data .= "成功しました<br />";
				break;
			case "error":
				$return_data .= "失敗しました<br />";
				break;
			case "format-ok":
				$return_data .= "初期化しました<br />";
				break;
			case "format-error":
				$return_data .= "初期化に失敗しました<br />";
				break;
			case "insert-ok":
				$return_data .= "新規作成しました<br />";
				if(isset($_REQUEST['total'])){
					$return_data .= '合計：'.number_format(esc_html($_REQUEST['total'])).'件&nbsp;&nbsp;';
				}
				if(isset($_REQUEST['ins'])){
					$return_data .= '新規：'.number_format(esc_html($_REQUEST['ins'])).'件&nbsp;&nbsp;';
				}
				if(isset($_REQUEST['dup'])){
					$return_data .= '重複：'.number_format(esc_html($_REQUEST['dup'])).'件&nbsp;&nbsp;';
				}
				break;
			case "insert-ng":
				$return_data .= "新規作成に失敗しました<br />";
				break;
			case "update-ok":
				$return_data .= "更新に成功しました<br />";
				break;
			case "update-ng":
				$return_data .= "更新に失敗しました<br />";
				break;
			case "update-op-ng":
				$return_data .= "変更点がないか更新に失敗しました<br />";
				break;
			case "write-ok":
				$return_data .= "編集に成功しました<br />";
				break;
			case "write-ng":
				$return_data .= "編集に失敗しました<br />";
				break;
			case "delete-ok":
				if(!empty($_GET['id'])){
					$return_data .= "id".esc_html($_GET['id'])."の";
				}
				$return_data .= "削除に成功しました<br />";
				break;
			case "delete-ng":
				$return_data .= "削除に失敗しました<br />";
				break;
			case "write-user-ng":
				$return_data .= "編集権限のないユーザです<br />";
				break;
			case "nonce-ng":
				$return_data .= "POST認証に失敗しました<br />";
				break;
			case "password-ok":
				$return_data .= "パスワードの認証に成功しました<br />";
				break;
			case "password-ng":
				$return_data .= "パスワードの認証に失敗しました<br />";
				break;
			case "repassword-ok":
				$return_data .= "パスワードを再発行しました。メールをご確認ください。<br />";
				break;
			case "repassword-ng":
				$return_data .= "パスワードの再発行に失敗しました<br />";
				break;
			case "usermeta-ok":
				$return_data .= "ユーザ情報の更新に成功しました<br />";
				break;
			case "usermeta-ng":
				$return_data .= "ユーザ情報の更新に失敗しました<br />";
				break;
			case 'message':
				$return_data .= (isset($_GET['text'])) ? esc_html($_GET['text']): '';
				break;
		}

		return $return_data;

	}
	//
	public static function _viewUpdateMessage($msg=''){

		$return_data = '';

		switch($msg){

			case "error":
				$return_data .= "失敗しました<br />";
				break;
			case "dg-error":
				$return_data .= "診断に失敗失敗しました<br />";
				break;

		}

		return $return_data;

	}
	/*
	*  検索時のメッセージ
	*/
	public static function search_message($msg_array=array()){

		$msg = '';
		//
		if(!empty($_REQUEST)){
			foreach($_REQUEST as $key => $req){
				if(isset($msg_array[$key]) && $req!=''){
					$msg .= str_replace(array('%%key%%', '%%value%%'), array(esc_html($key), esc_html($req)), $msg_array[$key]);
				}
			}
			$msg = rtrim($msg, "、");
			$msg = rtrim($msg, ",");
		}

		return $msg;

	}

}
?>