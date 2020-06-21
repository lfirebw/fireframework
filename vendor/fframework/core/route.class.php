<?php
class route {
    static private $controller;
    static private $action;
    static private $listRouting;
    static private $listRoutingIgnore;
    static private $listRoutingExtension;
    static private $uri;
    static private $params;
    static private $filePath;
    static private $pathIgnore;
    static private $pathExtension;
    static private $nameExtension;
    static private $routeExtensionPath;

    private static function initVariables(){
        self::$listRouting = array();
        self::$listRoutingIgnore = array();
        self::$listRoutingExtension = array();
        self::$params = array();
        self::$uri = "/";
        self::$controller = "index";
        self::$action = "index";
        self::$pathIgnore = '';
        self::$pathExtension = '';
        self::$nameExtension = '';
        self::$routeExtensionPath = array();
    }

    public static function initialize(){
        try{
            //check if route in the config is active
            if( isset($GLOBALS['config']['general']['route']) && !empty($GLOBALS['config']['general']['route'])  ){
                $_routepath = rtrim($GLOBALS['config']['general']['route'],'/');
                //check file is exist
                if(file_exists(ROOT.$_routepath.DS."route.php")){
                    self::$filePath = ROOT.$_routepath.DS."route.php";
                    self::initVariables();
                    self::importRouting();
                    //here check extension	
                    self::importExtension();
                    return true;
                }
            }//end if;
            return false;
        }catch(Exception $e){
          return "Error on route : ".$e->getMessage()." Line: ".$e->getLine();
        }
    }

    private static function importExtension(){	
        //its void function because is not return any value	
        if(!empty(self::$routeExtensionPath)){	
            foreach(self::$routeExtensionPath as $path){	
                @include_once($path);	
            }	
        }	
    }

    public static function importRouting(){
        //get rules from file
        @include_once(self::$filePath);
    }
    public static function verify(){
        try{
            if(!empty(self::$listRouting)){
                //here check extension	
                self::importExtension();
                $tmp_uri = (!empty(self::$nameExtension)) ? "/".self::$nameExtension : '';
                //buscar en el array cada url
                foreach(self::$listRouting as $value){
                    $uri1 = $tmp_uri.$value['url'];
                    $uri2 = self::$uri;
                    $prepareParams = array();
                    //descomponer el url para identificar comprobaciones
                    if(substr_count($uri1,'/') == substr_count($uri2,'/')){
                        //verificar si existe ruta con parametros
                        if(strpos($uri1,':') !== false){
                            $arrURI = explode('/',$uri1);
                            $arrUserURI = explode('/',$uri2);
                            $i = 0; $c = count($arrURI);
                            do{
                                if(strpos($arrURI[$i],':') !== false){
                                    $kparams = substr($arrURI[$i], 1);
                                    $arrURI[$i] = $arrUserURI[$i];
                                    $prepareParams[$kparams] = $arrUserURI[$i];
                                }
                                ++$i;
                            }while($i < $c);
                            $uri1 = implode('/',$arrURI);
                            $uri2 = implode('/',$arrUserURI);
                           
                        }
                    }
                    if(strcasecmp($uri1,$uri2) === 0){
                        self::$controller = $value['controller'];
                        self::$action = $value['action'];
                        self::$params = $prepareParams;
                        return true;
                    }
                }
            }
            return false;
        }catch(Exception $e){
            return "Error on route : ".$e->getMessage()." Line: ".$e->getLine();
        }
    }
    public static function verifyIgnores($_Uri = null){
        try{
            $_Uri = empty($_Uri) ? ltrim(self::$uri,'/')  : ltrim($_Uri,'/');
            
            if(!empty(self::$listRoutingIgnore) && !empty($_Uri)){
                //buscar
                $find = array_search($_Uri,self::$listRoutingIgnore);
                if($find !== false){
                    self::$pathIgnore = "/".self::$listRoutingIgnore[$find];
                    return true;
                }
            }
            return false;
        }catch(Exception $e){
            echo "Error on route : ".$e->getMessage()." Line: ".$e->getLine();
            return false;
        }
    }
    public static function verifyExtension($_Uri = null){
        try{
            $_Uri = empty($_Uri) ? ltrim(self::$uri,'/')  : ltrim($_Uri,'/');
            //obtener la primera posicion de / en el uri
            $countSlash = substr_count($_Uri,'/');
            if(!empty($_Uri) && $countSlash >= 1){
                $tmp_uriArr = explode('/',$_Uri);
                $_Uri = $tmp_uriArr[0];
            }
            if(!empty(self::$listRoutingExtension) && !empty($_Uri)){
                //buscar
                $find = array_search($_Uri,self::$listRoutingExtension);
                if($find !== false){
                    self::$pathExtension = self::$listRoutingExtension[$find];
                    self::$nameExtension = basename(self::$listRoutingExtension[$find]);
                    return true;
                }
            }
            return false;
        }catch(Exception $e){
            echo "Error on route : ".$e->getMessage()." Line: ".$e->getLine();
            return false;
        }
    }
    public static function add($url, $controller){
        try{
            $r_separator = explode("@",$controller);
            $_controller = $r_separator[0];
            $_action = isset($r_separator[1]) && !empty($r_separator[1]) ? $r_separator[1] : 'index';
    
            self::$listRouting[] = array('url'=>$url,'controller'=>$_controller,'action'=>$_action);
        }catch(Exception $e){
            echo $e->getFile()." : ".$e->getMessage()." - on line ".$e->getLine();
            return false;
        }
    }
    public static function ignore($path){
        try{
            self::$listRoutingIgnore[] = ltrim($path,'/') ;
        }catch(Exception $e){
            echo $e->getFile()." : ".$e->getMessage()." - on line ".$e->getLine();
            return false;
        }
    }
    public static function extension($folder){
        try{
            self::$listRoutingExtension[] = ltrim($folder,'/');
        }catch(Exception $e){
            echo $e->getFile()." : ".$e->getMessage()." - on line ".$e->getLine();
            return false;
        }
    }
    public static function getExtensionPath(){
        return self::$pathExtension;
    }
    public static function getExtensionName(){
        return self::$nameExtension;
    }
    public static function getIgnorePath(){
        return self::$pathIgnore;
    }
    public static function getController(){
        return self::$controller;
    }
    public static function getAction(){
        return self::$action;
    }
    public static function getParams(){
        return self::$params;
    }
    public static function setURI($URI){
        if(!empty($URI)){
            self::$uri = $URI;
        }
    }
    public static function setExtensionRoute($pathfile){	
        if(!is_file($pathfile)){	
            return false;	
        }
        self::$routeExtensionPath[] = $pathfile;
    }
    public static function redir($dir){
		header("Location: ".$dir);
		exit(0);	
	}
    
}

?>