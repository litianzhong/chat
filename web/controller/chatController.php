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
		$i = 0;
		while ( true ) {
			$info = cache::get ( $id );
			$message = $info ["message"];
			$userstat=cache::get($id."login");
			$state=$userstat["state"];
			if (! empty ( $message )||$state=="1") {//if has message or userlist change
				$info["message"]=array();
				if($state=="1"){
					$userstat["state"]="0";
					cache::set ( $id."login", $userstat );
				}
				cache::set ( $id, $info );
				if($state=="1"){
					$userlist=cache::get("userlist");
				}else{
					$userlist=array();
				}
				$retMsg=array("user"=>$userlist,"message"=>$message);
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
			parent::$LogUtil->log($key);
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
}