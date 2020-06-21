<?php
class manager{

  static public function listDir($dir){
    $result = array();
    if(count(glob($dir)) !== 0){
      foreach(glob($dir) as $value){
        $time = filectime($value);
        $timem = filemtime($value);
        $filesize = filesize($value);
        $filename = basename($value);
        $type = strpos($filename, '.') !== false ? 'file' : 'folder';
        $result[] = array('filename'=>$filename,'type'=>$type,'size'=>$filesize,'createtime' => date('Y-m-d H:s:i',$time),'modifytime' => date('Y-m-d H:s:i',$timem));
      }
    }
    return $result;
  }

  static public function makeDir($dir){
    if(!is_dir($dir)){
      return mkdir($dir,0777,true);
    }
    return false;
  }

  static public function sizeDir($dir){
    $_dir = rtrim(str_replace('\\','/',$dir),'/');
    if(is_dir($dir)){
      $totalSize = 0;
      $_system = substr(PHP_OS,0,3);
      //check if is window
      if(strcasecmp($_system,'WIN') === 0 && extension_loaded('com_dotnet')){
        //process for windows
        $obj = new \COM('scripting.filesystemobject');
        if(is_object($obj)){
          $ref = $obj->getfolder($dir);
          $totalSize = $ref->size;
          $obj = null;
          return $totalSize;
        }
      }
      //check if is linux or mac os
      if(strcasecmp($_system,'WIN') !== 0){
        $io = popen('/usr/bin/du -sb '.$dir,'r');
        if($io !== false){
          $totalSize = intval(fgets($io,80));
          pclose($io);
          return $totalSize;
        }
      }
      //for all case
      $files = new \RecursiveIterator(new \RecursiveDirectoryIterator($dir));
      foreach($files as $value){
        $totalSize += $value->getSize();
      }
      return $totalSize;
    }elseif(is_file($dir)){
      return filesize($dir);
    }else{
      return 0;
    }
  }

}
?>
