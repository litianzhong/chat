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
		
		$sql="select name from member where id=? and password=?";
		$flag=false;
		$name="";
		$result=$this->db->valueQuery($sql,array($user->username,$user->userpwd));
		if($result!=null){
			$flag=true;
		}
		return array("flag"=>$flag,"name"=>$result);
	}
}
