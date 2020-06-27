<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Library\Apitools AS API;

class indexController{

	public function __construct(){
		// parent::__CONSTRUCT();
	}

	public function index(Request $request, Response $response, array $args = []) : Response {
		try{
			return API::printJSON($response,200,"Request Completed",array());
		}catch(Exception $e){
			return $e->getMessage();
		}
	}
}
?>