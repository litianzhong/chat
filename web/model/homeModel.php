<?php
/**
 * 
 * 登录控制页
 * @author      李天中
 * @version     1.0
 */
class homeModel extends Model {
	/**
	 * 读取文件校验用户是否正确
	 * 校验用户是否正确
	 *
	 * @param $user 用户        	
	 */
	public function checkUser($user) {
		$sql = "select name from member where id=? and password=?";
		$flag = false;
		$name = "";
		$result = $this->db->valueQuery ( $sql, array (
				$user->username,
				$user->userpwd 
		) );
		if ($result != null) {
			$flag = true;
		}
		return array (
				"flag" => $flag,
				"name" => $result 
		);
	}
	/****
	 * 
	 * @param  $user
	 * @return multitype:boolean 
	 */
	public function countLogin($user) {
		$sql = "select count(1) from session where id=? and flag='1'";
		$name = "";
		$result = $this->db->valueQuery ( $sql, array (
				$user->username
		) );
		
		return $result;
	}
	/**
	 * update or insert session table
	 * @param String $id        	
	 */
	public function dbLogin($id, $name) {
		$isql = "insert into session VALUES(?, ?, ?, CURRENT_TIMESTAMP,?)";
		$usql = "update session  set flag='1',state='1',time=CURRENT_TIMESTAMP where id=?";
		$ssql = "select count(1) from session where id=?";
		$result=$this->db->valueQuery ( $ssql, array ($id ) );
		if  ($result> 0) {
			$this->db->execute ( $usql, array (
					$id 
			) );
		} else {
			$this->db->execute ( $isql, array (
					$id,
					'1',
					'1',
					$name 
			) );
		}
	}
	/**
	 * *
	 * update others
	 * @param String $id        	
	 */
	public function updateLogin($id) {
		$sql = "update session set state='1' where flag='1' and id<>?";
		$this->db->execute ($sql,array($id));
	}
}
