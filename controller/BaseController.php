<?php
class BaseController 
{
    protected $twig;
    protected $sqlCommande;

    function __construct($twig, $sqlCommande)
    {
        $this->twig = $twig;
        $this->sqlCommande = $sqlCommande;
    }

    function render($template, $data)
    {
        return $this->twig->render($template, $data);
    }

    
}