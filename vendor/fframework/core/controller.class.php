<?php

class controller {
  protected $loader;
  protected $vars = [];
  protected $layout = 'default';
  protected $schema = null;
  protected $cssFiles = [];
  protected $scriptFiles = [];

  public function __CONSTRUCT(){

    $this->schema = substr(get_class($this),0, -10);
    $this->loader = new Loader();
    $this->setConfig();
  }
  public function redirect($url,$message,$wait = 0){
    if($wait == 0){
      header("Location:".$url);
    }else{
      include(firecore::getCURR_VIEW_PATH()."message.html");
    }

    exit(0);
  }
  public function setVars($v){
    $this->vars = array_merge($this->vars,$v);
  }
  public function setVariable($v,$n){
    $this->vars[$n] = $v;
  }
  public function importCSS($filename,$path=null){
    try{ 
      $_filename = preg_replace('/.css$/', '', $filename).".css";
      $_path = $path != null ? $path : '';
      $pathfile = $path != null ? PUBLIC_PATH."assets".DS."css".DS.rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path),DS).DS.$_filename  : PUBLIC_PATH."assets".DS."css".DS.$_filename;
      if(!file_exists($pathfile)){
        throw new Exception("{$filename} not found");
      }
      $this->cssFiles[] = '<link rel="stylesheet" href="'.URL_ASSETS.'/assets/css/'.$_path.$_filename.'" /> ';
      return true;
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function importJS($filename,$path=null){
    try{ 
      $_filename = preg_replace('/.js$/', '', $filename).".js";
      $_path = $path != null ? $path : '';
      $pathfile = $path != null ? PUBLIC_PATH."assets".DS."js".DS.rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path),DS).DS.$_filename  : PUBLIC_PATH."assets".DS."js".DS.$_filename;
      if(!file_exists($pathfile)){
        throw new Exception("{$filename} not found");
      }
      $this->scriptFiles[] = '<script src="'.URL_ASSETS.'/assets/js/'.$_path.$_filename.'"></script>';
      return true;
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  private function getConfig(){
    return (file_exists($path)) ? @include(CONFIG_PATH."general.php"): null;
  }
  private function setConfig(){
    $_config = (isset($GLOBALS['config']['general'])) ? : $this->getConfig();
    $this->layout = (isset($GLOBALS['config']['general']['layout'])) ? $GLOBALS['config']['general']['layout'] : $this->layout;
    return true;
  }
  private function getCssFile($html = null){
    $html = empty($html) ? file_get_contents(firecore::getCURR_VIEW_PATH().$this->schema.".php") : $html;
    $_tmp_css = array();
    $matchesCss = false;
    $rcss = preg_match_all('#<CSSFile\ filename="([^"]+)"(.+)(.*)\/>#iU', $html, $matchesCss);
    if(!empty($matchesCss) && !empty($matchesCss[0][0])){
      foreach($matchesCss as $key => $value){
        if($key == 1){
          foreach($value as $cssFile){
            $_tmp_css[] = $cssFile;
          }
        }
      }//end foreach
    }//end if
    return $_tmp_css;
  }
  private function getJsFile($html = null){
    $html = empty($html) ? file_get_contents(firecore::getCURR_VIEW_PATH().$this->schema.".php") : $html;
    $scripts = array();
    $matches = false;
    $r = preg_match_all('#<JScript\ filename="([^"]+)"(.+)(.*)\/>#iU', $html, $matches);

    if(!empty($matches) && !empty($matches[0][0])){
      foreach($matches as $key => $value){
          if($key == 1){
            foreach($value as $jsFile){
              $_tmp_js = file_get_contents(firecore::getJAVASCRIPT_PATH().$jsFile);
              $scripts[] = "<script type='text/javascript'>{$_tmp_js}</script>";
            }
          }
      }
    }//end if
    return $scripts;
  }
  private function getHeadStyle(){
    //pintar los css
    if(!empty($this->cssFiles)){
      foreach($this->cssFiles as $css){
        echo $css;
      }
    }
  }
  private function getBodyScript(){
    //pintar los script
    if(!empty($this->scriptFiles)){
      foreach($this->scriptFiles as $js){
        echo $js;
      }
    }
  }
  private function getChildHtml($param){
    extract($this->vars);
    $this->jsonVars = json_encode($this->vars);
    if($param == 'content'){  
      $_html = file_get_contents(firecore::getCURR_VIEW_PATH().$this->schema.".php"); 
      
      $cssFiles = $this->getCssFile($_html);
      $scripts = $this->getJsFile($_html);

      if(!empty($cssFiles)){
        foreach($cssFiles as $cssFile){
          $_tmp_css = file_get_contents(firecore::getCSS_PATH().$cssFile);
          echo (!empty($_tmp_css)) ? "<style>{$_tmp_css}</style>" : '';
        }
      }

      //echo $_html;
      require_once(firecore::getCURR_VIEW_PATH().$this->schema.".php");

      if(!empty($scripts)){
        foreach($scripts as $script){
          echo $script;
        }
      }
    }
  }
  public function __set($name,$value){
    $this->$name = $value;
  }
  public function between($tag,$endtag){}
  public function render($onlyView = false){
    //$datos = ob_get_contents();
    //var_dump($datos);
    $this->title = $GLOBALS['config']['site']['name_app'];
    $this->copyright = $GLOBALS['config']['site']['copyright'];
    $this->description = $GLOBALS['config']['site']['description'];
    $this->keywords = implode(',', $GLOBALS['config']['site']['keywords']);

    //$jscript = strpos($_html, '<JScript');
    //if($jscript !== false){
      //$_tmp = substr($_html, $jscript);
    //}
    
    if(!$onlyView){
      require_once(firecore::getLAYOUT_PATH().$this->layout.".php");
    }else{
      $_html = file_get_contents(firecore::getCURR_VIEW_PATH().$this->schema.".php"); 
      
      $cssFiles = $this->getCssFile($_html);
      $scripts = $this->getJsFile($_html);

      if(!empty($cssFiles)){
        foreach($cssFiles as $cssFile){
          $_tmp_css = file_get_contents(firecore::getCSS_PATH().$cssFile);
          echo (!empty($_tmp_css)) ? "<style>{$_tmp_css}</style>" : '';
        }
      }

      //echo $_html;
      require_once(firecore::getCURR_VIEW_PATH().$this->schema.".php");

      if(!empty($scripts)){
        foreach($scripts as $script){
          echo $script;
        }
      }
    }
    //$renderedView = ob_get_clean();
    //ob_get_clean(); 
    //return $renderedView;
  }
}

?>
