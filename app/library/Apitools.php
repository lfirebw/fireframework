<?php
namespace App\Library;

use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Http\Response;
// use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;

class Apitools {
	static public function test(){
		echo "pudin";
	}
	static public function printJSON($response,$state,$msj,$data,$encodingOptions = 0){
		try{
			$response->withStatus($state);
			$response->getBody()->write(json_encode(array('code'=>$state,'state'=>$state,'message'=>$msj,'data'=>$data)));
			$newResponse = $response->withHeader(
		        'Content-type',
		        'application/json; charset=utf-8'
		    );
			return $newResponse;
		}catch(Exception $e){
			throw new RuntimeException('An unexpected error occurred while draw the json formated.');
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