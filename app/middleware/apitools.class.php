<?php

/**
 * Class middleware with all programming tools for apis
 */
class apitools
{
	static public function printJSON($state,$msj,$data){
		try{
			header("HTTP/1.1 " . $state);
			header('Content-Type: application/json');
			echo json_encode(array('code'=>$state,'state'=>$state,'message'=>$msj,'data'=>$data));
			exit(0);
		}catch(Exception $e){
			echo json_encode(array('code'=>400,'message'=>$e->getMessage(),'data'=>array()));
			exit(0);
		}
	}
	static public function requestHTTP($post_data,$target, $json = true){
		try{
			$ch = curl_init();
			if(strpos($target,'https') !== false){
				curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL,$target);
			curl_setopt($ch, CURLOPT_POST,1);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
			$result=curl_exec($ch);
			curl_close($ch);
			$p = ($json==true) ? json_decode($result,true) : $result ;
			if(isset($p['code']) && $p['code'] != 200){
				print_r($result);
				throw new Exception($result);
			}
			return $p;
		}catch(Exception $e){
			throw new Exception("Error in request : ".$e->getMessage());
		}
	}
	
}

?>