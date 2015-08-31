<?php
/***
 * chat room 
 * @author litz
 *
 */
require "cache.php";
class chatController extends Controller {
	const sleeptime = 500000;
	const time = 20;
	const exp = 30;
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * get msgs and userlist
	 */
	public function getMsgs() {
		set_time_limit ( 0 ); // 无限请求超时时间
		$i = 0;
		$id = Application::$_lib ["SessionAuth"]->get ( "_ID" );
		$username = Application::$_lib ["SessionAuth"]->get ( "_USER" );
		session_write_close ();
		/***
		 * 
		 */
		$model = $this->model ( "chat" ); // 获得model
		$model->updateLoginone ( $id ); // 更新登陆最新心跳时间
		if ($model->selectStateCount ( self::exp ) > 0) {
			$model->updateLogin ( self::exp );
			$model->updateNotice ();
		}
		
		$i = 0;
		while ( true ) {
			$sessionState = $model->selectLogin ( $id );
			parent::$LogUtil->log ( "test" . $sessionState );
			if ($sessionState == '1') { // 有变化
				$model->updateState ( $id );
			}
			$info = cache::get ( $id );
			$message = $info ["message"];
			if (! empty ( $message ) || $sessionState == "1") { // if has message or userlist change
				$info ["message"] = array ();
				cache::set ( $id, $info );
				if ($sessionState == "1") {
					$userlist = $model->selectUsers ();
				} else {
					$userlist = array ();
				}
				parent::$LogUtil->log ( print_r ( $userlist, true ) );
				$retMsg = array (
						"user" => $userlist,
						"message" => $message 
				);
				echo json_encode ( $retMsg );
				exit ();
			}
			usleep ( self::sleeptime );
			$i ++;
			parent::$LogUtil->log ( $i );
			if ($i * 2 == self::time) {
				echo json_encode ( "" );
				exit ();
			}
		}
	}
	/**
	 * send message
	 */
	public function sendMsg() {
		header ( 'Content-Type: application/json; charset=utf-8' );
		parent::$LogUtil->log ( print_r ( $_POST, true ) );
		$msg = $_POST ["msg"];
		$userid = Application::$_lib ["SessionAuth"]->get ( "_ID" );
		$username = Application::$_lib ["SessionAuth"]->get ( "_USER" );
		session_write_close ();
		$id = "userlist";
		$userList = cache::get ( $id );
		foreach ( $userList as $key => $value ) {
			parent::$LogUtil->log ( $key );
			$info = cache::get ( $key );
			$messages = $info ["message"];
			$message = $username . ":" . $msg;
			if (! empty ( $messages )) {
				$messages [] = $message;
			} else {
				$messages = array (
						$message 
				);
			}
			$info ["message"] = $messages;
			cache::set ( $key, $info );
		}
		echo json_encode ( "" );
		exit ();
	}
	/**
	 * *
	 * check user is login;
	 */
	private function checkLogin() {
		$time = microtime ( true );
		cache::set ( $id . "session", $userstat );
	}
}