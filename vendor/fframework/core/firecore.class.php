<?php

class firecore{
  static protected $remplace = null;
  static protected $isLogin = false;
  static protected $controller = 'index';
  static protected $action = 'index';
  static private $isRouting = false;
  static private $HTTPS = false;
  static private $extension = '';
  static public $public_folder = '/public';
  /**
   * Dynamic path variable at the core
   */
  static protected $CONTROLLER_PATH;
  static protected $VIEW_PATH;
  static protected $CURR_CONTROLLER_PATH;
  static protected $CURR_VIEW_PATH;
  static protected $JAVASCRIPT_PATH;
  static protected $CSS_PATH;
  static protected $LAYOUT_PATH;


  public static function run(){
    //echo "run()";
    try{
      self::defines();
      self::init();
      self::request();
      self::autoload();
      if(self::$isLogin === true){
        Import::Middleware('session');
        session::checkSession();
      }
      self::dispatch();
    }catch(Exception $e){
      printf("Fatal Error Core: %s",$e->getMessage());
      exit(0);
    }
  }
  private static function defines(){
    //define the core routes
    define("DS", DIRECTORY_SEPARATOR);
    $root = realpath(getcwd());
    $checkRoot = basename($root);
    if(strcmp($checkRoot,'public')=== 0){
      $root = substr($root,0, -strlen(DS.'public'));
    }else if(strcmp($checkRoot,self::$remplace)=== 0){
      $root = substr($root,0, -strlen(DS.self::$remplace));
    }
    
    define("ROOT",$root.DS);
    define("STORAGE_PATH",ROOT."storage".DS);
    define("APP_PATH",ROOT . "app".DS);
    define("FRAMEWORK_PATH",ROOT . "vendor". DS);
    define("PUBLIC_PATH",ROOT . "public" . DS);
    define("CONFIG_PATH",APP_PATH . "config". DS);
    define("MODEL_PATH",APP_PATH . "models" . DS);
    define("CORE_PATH", FRAMEWORK_PATH . "core". DS);
    define("DB_PATH", FRAMEWORK_PATH . "database" . DS);
    define("LIB_PATH", FRAMEWORK_PATH . "libraries" . DS);
    define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);
    define("UPLOAD_PATH", PUBLIC_PATH . "uploads". DS);
    
    self::$CONTROLLER_PATH = APP_PATH . "controllers" .DS;
    self::$VIEW_PATH = APP_PATH . "views". DS;
    self::$JAVASCRIPT_PATH = APP_PATH. "javascripts".DS;
    self::$CSS_PATH = APP_PATH. "styles".DS;
    self::$LAYOUT_PATH = self::$VIEW_PATH. "layout" . DS;
  }
  private static function setMVCRouting($url,$nIgnore = 1){
    global $urlarray;
    $urlarray = array_slice(explode('/', $url),$nIgnore);
    //use mvc routing
    self::$controller = isset($urlarray[0]) && !empty($urlarray[0]) ? $urlarray[0] : 'index';
    self::$action = isset($urlarray[1]) && !empty($urlarray[1]) ? $urlarray[1] : 'index';
  }
  private static function request(){
    $http = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ;
    $_http_host = $_SERVER['HTTP_HOST'];
    $lengthMinusOne = strlen($_http_host)-1;
    $host = (strcmp(substr($_http_host, $lengthMinusOne), '/') === 0) ? substr($_http_host,0,$lengthMinusOne)  : $_http_host;
    
    if(!empty($GLOBALS['config']['general']['root'])){
      if(strpos($GLOBALS['config']['general']['root'], 'public') !== false){
        self::$public_folder = '';
      }else{
        self::$public_folder = $GLOBALS['config']['general']['root'].self::$public_folder;
      }
    }
    
    if(isset($GLOBALS['config']['general']['isLogin'])){
      self::$isLogin = $GLOBALS['config']['general']['isLogin'];
    }
    $tmp_URL_WEB = $http.$host.$GLOBALS['config']['general']['root'];
    
    $url = $_SERVER['REQUEST_URI'];
    $url = (strstr($url, '?')) ? substr($url, 0,strpos($url,'?')) : $url;
    //importar la configuracion personalizada
    $_tmpconfig = $GLOBALS['config']['general']['root'];
    if(isset($_tmpconfig) && !empty($_tmpconfig)){
      if(strpos($url, $_tmpconfig) >= 0){
      //if(strcmp($url, $_tmpconfig) === 0){
        $url = str_replace($_tmpconfig, '', $url);
      }
    }
    unset($_tmpconfig);
    self::setMVCRouting($url);
    //configuracion actual
    $config_now = $GLOBALS['config']['general'];
    if(isset($GLOBALS['config']['general']['requireHTTPS'])){
      self::$HTTPS = $GLOBALS['config']['general']['requireHTTPS'];
    }
    if(route::initialize()){
      route::setURI($url);
      //Find ignore path and redir if it's necessary 
      
      if(route::verifyIgnores()){
        $uriIgnore = route::getIgnorePath();
        header("Location: ".$tmp_URL_WEB.$uriIgnore);
        exit(0);
      }
      //Find extension and set variables
      if(route::verifyExtension()){
        $root_extension = ROOT.route::getExtensionPath().DS;
        self::$CONTROLLER_PATH = $root_extension."controllers".DS;
        self::$VIEW_PATH = $root_extension."views".DS;
        self::$LAYOUT_PATH = self::$VIEW_PATH. "layout" . DS;
        $extension_name = route::getExtensionName();
        self::$extension = "/{$extension_name}";
        self::setMVCRouting($url,2);
        $GLOBALS['config']['general'][$extension_name] = @include( $root_extension."config".DS."general.php");
        $config_now = $GLOBALS['config']['general'][$extension_name];
        if(isset($GLOBALS['config']['general'][$extension_name]['isLogin'])){
          self::$isLogin = $GLOBALS['config']['general'][$extension_name]['isLogin'];
        }
        if(isset($GLOBALS['config']['general'][$extension_name]['requireHTTPS'])){
          self::$HTTPS = $GLOBALS['config']['general'][$extension_name]['requireHTTPS'];
        }
        route::setExtensionRoute($root_extension."route.php");
      }
      if(route::verify()){
        self::$isRouting = true;

        self::$controller = route::getController();
        self::$action     = route::getAction();
      }else{
        if(isset($config_now['onlyRouting']) && $config_now['onlyRouting'] == true){
          throw new Exception("URL not found");
        }
      }
    }//endif route::initialize
    if(self::$HTTPS == true && empty($_SERVER['HTTPS'])){
      route::redir("https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    }
    //var_dump($action);
    define("CONTROLLER", self::$controller);
    define("ACTION",self::$action);
    define('URL_ASSETS', $http.$host.self::$public_folder);
    define('URL_WEB', $http.$host.$GLOBALS['config']['general']['root'].self::$extension);
    define('URL_BASE',rtrim($http.$host.$GLOBALS['config']['general']['root'],'/'));
    self::$public_folder = null;
    unset($lengthMinusOne);
    unset($_http_host);
    unset($http);
  }

  private static function init(){    
    //load core class
    try{
      require(CORE_PATH . "controller.class.php");
      require(CORE_PATH . "loader.class.php");
      require(DB_PATH . "connection.class.php");
      require(DB_PATH . "db.class.php");
      require(CORE_PATH . "model.class.php");
      require(CORE_PATH . "route.class.php");
      require(CORE_PATH . "request.class.php");
      require(CORE_PATH . "import.class.php");

      //load configuration
      $GLOBALS['config']['db'] = @include CONFIG_PATH . "db.php";
      $GLOBALS['config']['site'] = @include(CONFIG_PATH . "site.php");
      $GLOBALS['config']['general'] = @include(CONFIG_PATH . "general.php");

      date_default_timezone_set('America/Lima');
      
      //start sesion
      session_start();
    }catch(Exception $e){
      return $e->getMessage();
    }
  }
  //autoloading
  private static function autoload(){
    spl_autoload_register(array(__CLASS__,'load'));
  }

  private static function load($classname){
    try{      
      //var_dump($classname);
      //$_name = explode('\\', $classname);
      //$classname = end($_name);
      if(substr($classname,-10)=='Controller'){
        require_once(firecore::getCONTROLLER_PATH() . $classname.".class.php");
      }

      //else if(substr($classname,-5) == 'Model'){
        //require_once(MODEL_PATH.$classname.".class.php");
      //}
    }catch(Exception $e){
      return $e->getMessage();
    }
  }
  private static function getParams(){
    global $urlarray;
    $result = null;
    if(count($urlarray) > 2){
      for ($i=2; $i < count($urlarray); $i++)
        $result[] = $urlarray[$i];
    }
    return $result;
  }
  private static function dispatch(){
    $controller_name = CONTROLLER."Controller";
    $action_name = ACTION."Action";
    $controller = new $controller_name;
    $params = self::$isRouting == true ? route::getParams() : self::getParams();
    
    request::setParams($params);

    $reflection = new ReflectionMethod($controller_name,$action_name);
    if($reflection->getNumberOfParameters() > 0  && !empty($params) ){
      call_user_func_array(array($controller,$action_name),$params);
    }else{
      $controller->$action_name();
    }
  }

  /**
   * Sets
   */
  public static function setRemplace($str){
    self::$remplace = ltrim($str,'/');
  }
  public static function setIsLogin($bool){
    self::$isLogin = $bool;
  }
  public static function setExtensionRoot($string){
    self::$extension = $string;
  }
  public static function requireHTTPS($val){
    self::$HTTPS = $val;
  }
  /**
   * Gets
   */
  public static function getCONTROLLER_PATH(){
    return self::$CONTROLLER_PATH;
  }
  public static function getJAVASCRIPT_PATH(){
    return self::$JAVASCRIPT_PATH;
  }
  public static function getCSS_PATH(){
    return self::$CSS_PATH;
  }
  public static function getVIEW_PATH(){
    return self::$VIEW_PATH;
  }
  public static function getLAYOUT_PATH(){
    return self::$LAYOUT_PATH;
  }
  public static function getCURR_CONTROLLER_PATH(){
    return self::$CONTROLLER_PATH;
  }
  public static function getCURR_VIEW_PATH(){
    return self::$VIEW_PATH;
  }
}
?>
