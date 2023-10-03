<?php

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class RenderBinsta
{
    private $load;
    private $view;

    public function __construct($directory)
    {
        $this->load = new FilesystemLoader($directory);
        $this->view = new Environment($this->load);
    }

    public function render($viewname, $content)
    {
        echo $this->view->render($viewname, $content);
    }
}

function displayView($viewDirectory, $viewName, $content)
{
    $renderer = new renderBinsta($viewDirectory);
    $renderer->render($viewName, $content);
}

function errorHandle($errorType, $errorMessage)
{
    $renderer = new renderBinsta('../views/files');
    http_response_code($errorType);
    $renderer->render('error.twig', ['errorMessage' => $errorMessage]);
    exit;
}