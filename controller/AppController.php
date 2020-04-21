<?php
require_once(__DIR__.'/BaseController.php');

class AppController extends BaseController {
    

    /*******************************************
     *           ACCES PUBLIC
    ********************************************/
    // Page d'accueil
    public function home()
    {
        echo $this->render('home.html.twig',
            ['title' => '','is_userConnect' => $this->get_value_session()]);
    }
    
    // Rendu de la liste des fiches 
    public function listeFiches($currentPage, $isAllFiche)
    {
        $list = $this->sqlCommande->listeFiches($currentPage,$isAllFiche);
        if($isAllFiche)$currentPage = 0;
        if($currentPage <= $list['nbrePage'])
        {
            echo $this->render('users/listeFiches.html.twig', 
            ['title' => 'Guide des champignons',
            'is_userConnect' => $this->get_value_session(),
            'listeFiches' => $list['listFiches'],
            'pagination' => $list['nbrePage'],
            'currentPage' => $currentPage
            ]);
        }
        else
        {
            throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "fiches");
        }  
    }

    // Voir une fiche avec restriction en fonction du role ou en session public
    public function getFiche($role,$iduser,$idchamp)
    {
        $fiche = $this->sqlCommande->getFiche($role,$iduser,$idchamp);
        if($fiche)
        {
            $images = $this->getImages($fiche);
            echo $this->render('users/ficheCarousel.html.twig',
            [
                'title' => 'Description',
                'is_userConnect' => $this->get_value_session(),
                'fiche' => $fiche[0],
                'images' => $images
            ]); 
        }
        else
        {
            throw new ExceptionWithRedirect("Cette page n'existe pas ou vous ne disposez pas des droits necessaires !", 404, "fiches");
        }
    }   

    // retourne un tableau d'images
    public function getImages($fiche)
    {
        if ($fiche)
            {
                $images=[];
                foreach($fiche as $image)
                {
                    if($image['pathimg'] != "")
                    {
                        array_push($images,"/assets/images/photo-fullscreen/".$image['idchamp'].'/maxSize/'.$image['pathimg']);
                    } 
                }
                if(empty($images))
                {
                    $images[0] = "/assets/images/photo-fullscreen/default.png";
                }
                return $images;
            } 
            else
            {
                throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "fiches");
            }   
    }
    
    /*********************************************************
     *       DETERMINE SI UN UTILISATEUR EST CONNECTE 
     ********************************************************/
    function get_value_session()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user'])? $_SESSION['user'] : FALSE;
    }
    
    /*********************************************************
     *                 GESTION DES ERREURS
     ********************************************************/
    function handleError(Exception $e){
        echo $this->render('erreur.html.twig',[
                'error' => $e,
                'with_redirect' => is_a($e, "ExceptionWithRedirect")
            ]);
    }
}