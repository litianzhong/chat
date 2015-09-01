<?php
class chatController extends Controller {
	const sleeptime = 1;
	const exp = 30;
	public function __construct() {
		parent::__construct ();
	}
	/***
	 * 查询数据信息
	 */
	public function corntable() {
		register_shutdown_function(array('chatController','flush'));
		while ( true ) {
			$model = $this->model ( "chat" ); // 获得model
			if ($model->selectStateCount ( self::exp ) > 0) {
				$model->updateLogin ( self::exp );
				$model->updateNotice ();
			}
			parent::$LogUtil->log("corncorn");
			sleep ( self::sleeptime ); // sleep time
		}
	}
	
	static function flush(){
		$model = $this->model ( "chat" ); // 获得model
		$model->updateChatctrl();
	}
}