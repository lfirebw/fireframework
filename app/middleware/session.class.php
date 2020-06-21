<?php
class session {
	protected static $mySession;
	protected static $ignoreController = ''; //eg: controller/view

	static public function checkSession(){
		$continuar = false;
		/* @Check if controller is ignored */
		if(strpos(self::$ignoreController, CONTROLLER) !== false){
			$arr_tmp = explode(',', self::$ignoreController);
			if(!empty($arr_tmp)){
				$i = 0;
				$count = count($arr_tmp);
				do{
					//@Verify Controller
					$_controller = strstr($arr_tmp[$i], '/',true);
					if(strcmp($_controller, CONTROLLER) === 0){
						$_action = (strpos($arr_tmp[$i], '/') !== false) ? substr(strstr($arr_tmp[$i], '/'), 1) : null;
						if(strcmp($_action, ACTION) === 0 || strcmp($_action, '*') === 0){
							$continuar = true;
							break;
						}
					}
					++$i;
				}while($i < $count);
			}
		}//end if
		
		if($continuar === false){
			if(!isset($_SESSION['session']) || is_null($_SESSION['session'])){
				if($_SERVER['REQUEST_METHOD'] === 'GET'){
		      		echo CONTROLLER != 'login' ? '<script type="text/javascript">window.location.replace("'.URL_WEB.'/login")</script>' : null;
					
				}
			}else{
				//check if estado is 2 , then redir to change password
				if($_SESSION['estado'] == 2 && $_SERVER['REQUEST_METHOD'] === 'GET'){
					echo CONTROLLER != 'index' || ACTION != 'changepassword' ? '<script type="text/javascript">window.location.replace("'.URL_WEB.'/index/changepassword")</script>' : null;
				}
			}
		}
	}
	static public function insertSession(){
		self::$mySession = $_SESSION;
    return self::$mySession;
	}
	static public function getSession(){
		return (isset(self::$mySession) && !empty(self::$mySession)) ? self::$mySession : self::insertSession();
	}
	static public function removeSession(){
		if(!empty(self::$mySession)){
			self::$mySession = null;
		}
		return true;
	}
}
?>
