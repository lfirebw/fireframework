<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

abstract class AbstractTwigController extends AbstractController
{
    /**
     * @var Twig
     */
    protected $twig;
    /**
     * @var Layout
     */
    protected $layout;
    /**
     * AbstractController constructor.
     *
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
        $this->layout = "layout/default.twig";
    }

    /**
     * Render the template and write it to the response.
     *
     * @param Response $response
     * @param string   $template
     * @param array    $renderData
     *
     * @return Response
     */
    protected function render(Response $response, string $template, array $renderData = []): Response
    {
        $renderData['layout'] = "layout/{$this->layout}";
        return $this->twig->render($response, $template, $renderData);
    }
}