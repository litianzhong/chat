<?php
/**
 * 登录的处理
 * @author      李天中
 * @version     1.0
 */
require "cache.php";
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
        	if($model->countLogin($user)>0){// if user has login
        		$msg = array(
        				"code" => "0",
        				"tip" => "user has login!",
        		);//成功提示信息
        		echo json_encode($msg);
        		exit();
        	}
        	
        	$info=$model->checkUser($user);
        	$msg=array();
        	if($info["flag"]){
        		Application::$_lib["SessionAuth"]->set("_USER", $info["name"]);//校验成功放入session
        		Application::$_lib["SessionAuth"]->set("_ID", $user->username);//校验成功放入session
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
        	$userid=Application::$_lib["SessionAuth"]->get("_ID");
        	if($user){
        		/***notice user*/
        		$this->dbLogin($userid,$user);
        		$this->display("main.html");
        	}else{
        		$this->display("login.html");
        	}
        	
        }
        /***
         * forward register.html
         */
        public function forRegister(){
        		$this->display("register.html");
        	 
        }
        /***
         * validate id unique
         */
        public function valId(){
        	$id=$_POST["id"];//
        	$model=$this->model("home");//获得model
        	$result=$model->valId($id);
        	echo json_decode($result);
        
        }
        /***
         * register
         */
        public function register(){
        	$userid=$_POST["userid"];
        	$username=$_POST["username"];
        	$password=$_POST["password"];
        	$model=$this->model("home");//获得model
        	$result=$model->register($userid,$username,$password);//insert info
        	echo json_decode($result);
        }
        /***
         * record database
         * @param String $id
         * @param String $name
         */
        private function dbLogin($id,$name){
        	
        	$model=$this->model("home");//获得model
        	$model->dbLogin($id,$name);//update or insert login info
        	$model->updateLogin($id);//notice others
        }
        
        function runThread()
        {
        	$fp = fsockopen('127.0.0.1', 80, $errno, $errmsg);
        	fputs($fp, "GET chat/index.php/corn/corntablernrn");        //这里的第二个参数是HTTP协议中规定的请求头
        	fclose($fp);
        }
        
}

