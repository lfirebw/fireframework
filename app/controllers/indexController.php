<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Library\Apitools AS API;
use App\Library\Email AS EMAIL;
use App\Models\Test;

class indexController extends AbstractTwigController{

	/**
     * indexController constructor.
     *
     * @param Twig $twig
     */
	public function __construct(Twig $twig){
		parent::__construct($twig);
	}

	public function index(Request $request, Response $response, array $args = []) : Response {
		try{
			$data = Test::all();
			
			return API::printJSON($response,200,"Request Completed",$data);
		}catch(Exception $e){
			return $e->getMessage();
		}
	}
	/**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function twigtest(Request $request, Response $response, array $args = []): Response
    {
        return $this->render($response, 'test.twig', [
            'pageTitle' => "Template html",
            'Author' => "Emmy Seco",
        ]);
    }
	
}
?>