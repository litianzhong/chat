<?php
/**
 * 登录的处理
 * @author      李天中
 * @version     1.0
 */
class homeController extends Controller {
        public function __construct() {
                parent::__construct();
        }
        
		/**登录页*/
        public function index() {
			$this->display("login.html");
        }
        
        /****
         * validate ajax
         */
        public function check(){
			
        	header('Content-Type: application/json; charset=utf-8');
			parent::$LogUtil->log(print_r($_POST,true));
        	$model=$this->model("home");//获得model
        	$user=json_decode($_POST["model"]);
        	$info=$model->checkUser($user);
        	$msg=array();
        	if($info["flag"]){
        		Application::$_lib["SessionAuth"]->set("_USER", $user->username);//校验成功放入session
        		$msg = array(
        				"code" => "1",
        				"tip" => "",
        		);//成功提示信息
        	}else{
        		$msg = array(
        				"code" => "0",
        				"tip" => "username or password is wrong!",
        		);//成功提示信息
        	}
        	echo json_encode($msg);
        }
        /***
         * login ajax
         */
        public function login(){
        	$user=Application::$_lib["SessionAuth"]->get("_USER");
        	if($user){
        		$this->display("main.html");
        	}else{
        		$this->display("login.html");
        	}
        	
        }
}

