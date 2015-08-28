<?php
class online{
	public static $user=null;
	/***
	 * put user into userlist
	 * @param string $name
	 */
	public function putUser($id,$name){
		selef::$user[$id]=$name;
	}
	/***
	 * remove user from userlist
	 * @param string $name
	 */
	public function popUser($id){
		unset(selef::$user[$id]);
	}
	/***
	 * get all users from userlist
	 */
	public function getUser(){
		return self::$user;
	}
	
}