<?php
class ExceptionWithRedirect extends Exception {

    protected $redirect_url;

    function __construct($message = "", $code = 0, $redir_url = "portail")
    {
        parent::__construct($message, $code);
        $this->redirect_url = $redir_url;
    }

    public function getRedirectUrl(){
        return $this->redirect_url;
    }
}