<?php
require_once(__DIR__.'/AppController.php');

class AdminController extends AppController
{
    /************************************************
     *       GESTION DU COMPTE ADMINISTRATEUR
    ************************************************/
    // Affiche la page de l'administrateur
    public function showAccountAdmin($currentPageFiches,$currentPageUsers,$allFiche = false)
    {
        $listusers = $this->sqlCommande->listUsers($currentPageUsers);
        $listeFiches = $this->sqlCommande->listMushroomsPerUser($currentPageFiches,"",$allFiche);
        if($currentPageFiches <= $listeFiches[0] && count($listeFiches[1]) > 0)
        {
            echo $this->render('comptes/administrateur.html.twig', 
            ['title' => 'Administrateur',
            'is_userConnect' => $this->get_value_session(),
            'listeUser' => $listusers[1],
            'numberPageUsers' => $listusers[0],
            'currentPageUsers' => $currentPageUsers,
            'listeFiches' => $listeFiches[1],
            'numberPageFiches' => $listeFiches[0],
            'currentPageFiches' => $currentPageFiches,
            'stateAllFiche' => $allFiche,
            ]);
        } 
        else
        {
            throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "guide-des-champignons");
        }   
    }

    // Supprime un compte
    public function deleteAccount($idUser,$currentPageFiches,$currentPageUsers)
    {
        $response = $this->sqlCommande->deleteAccount($idUser);
        if($response)
        {
            $this->showAccountAdmin($currentPageFiches,$currentPageUsers); 
        }
        else
        {
            throw new ExceptionWithRedirect("Erreur lors de la suppression !", 401, "mon-compte"); 
        }
    }

    /*********************************************
    *    GESTION DES FICHES RENDRE PUBLIC, DELETE
    **********************************************/
    //  Supprime une fiche descriptive
    public function deleteMushroomOfAdmin($idchamp,$currentPgFiche)
    {
        // Supprime la fiche et les enregistrements enfants avec la CONTRAINT ON DELETE CASCADE
        $result = $this->sqlCommande->deleteMushroomOfAdmin($idchamp);
        // supprime les fichiers et les dossiers si la requete s'est executé.
        if($result)
        {
            try
            {
                $pathImage = $_SERVER['DOCUMENT_ROOT']."/assets/images/photo-fullscreen/";
                array_map('unlink', glob($pathImage.$idchamp."/minSize/"."*.*"));
                array_map('unlink', glob($pathImage.$idchamp."/maxSize/"."*.*"));
                if(file_exists($pathImage.$idchamp."/minSize")) rmdir($pathImage.$idchamp."/minSize");
                if(file_exists($pathImage.$idchamp."/maxSize")) rmdir($pathImage.$idchamp."/maxSize");
                if(file_exists($pathImage.$idchamp)) rmdir($pathImage.$idchamp);
            }
            catch (Exception $e) 
            {
                throw new ExceptionWithRedirect("Erreur impossible de supprimer les photos !", 401, "mon-compte");
            }
            header('location:index.php?routing=mon-compte&pageIndex='.$currentPgFiche);
        }
        else
        {
            throw new ExceptionWithRedirect("Erreur lors de la suppression !", 401, "mon-compte"); 
        }
    }

    // Autoriser ou masquer les fiches dans l'espace public
    public function authorizePublication($idchamp,$currentPgFiche)
    {
        $this->sqlCommande->authorizePublication($idchamp);
        header('location:index.php?routing=mon-compte&pageIndex='.$currentPgFiche);
         
    }
}