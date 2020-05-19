<?php
require_once(__DIR__.'/AppController.php');
require_once(__DIR__.'/../services/ResizeImage.php');

class MemberController extends AppController
{
    /************************************************
     *       GESTION DU COMPTE MEMBRE
    ************************************************/
    // Affiche la page d'administration des membres
    function showAccountMember($currentPageFiches,$idUser,$allFiche)
    {
        $listeFiches = $this->sqlCommande->listMushroomsPerUser($currentPageFiches,$idUser,$allFiche);
        if($currentPageFiches <= $listeFiches[0] && count($listeFiches[1]) > 0)
        {
            echo $this->render('comptes/member.html.twig', 
            ['title' => 'Membre',
            'is_userConnect' => $_SESSION['user'],
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

    // Rendu page modifier profil actif
    function showUpdateProfil($messageError="")
    {
        // $profil = $this->sqlCommande->getProfil($_SESSION['user']['iduser']);
        $profil = $_SESSION ['user'];
        echo $this->render('comptes/profil.html.twig', 
        ['title' => 'Modifier mon profil',
        'profil' => $profil,
        'messageError' => $messageError,
        'is_userConnect' =>  $this->get_value_session(),
        ]);
    }

    // Mettre à jour son profil
    function updateProfil($pseudo,$nom,$prenom)
    {
        $state = $this->sqlCommande->updateProfil($_SESSION['user']['iduser'],$pseudo,$nom,$prenom);
        if($state['success'])
        {
            $_SESSION['user']['pseudouser'] =$pseudo;
            $_SESSION['user']['nomuser'] =$nom;
            $_SESSION['user']['prenomuser'] =$prenom;
            $this->showUpdateProfil("Votre profil a été mis à jour.");
        }
        else
        {
            if ($state['is_exist_other_speudo'])
            {
                $this->showUpdateProfil("Impossible de modifier votre profil,Ce pseudo est déja utilisé, veuillez en choisir un autre.");
            }
            else
            {
                throw new ExceptionWithRedirect("Erreur, impossible de modifier votre compte !", 401, "mon-compte");
            }
        }
    }

    // Mettre à jour son mot de passe
    function updatePassword($oldPassword,$newPassword,$confirmNewpassword)
    {
        // Compare le nouveau mot de passe et celui de confirmation
        if ($confirmNewpassword === $newPassword)
        {
            // Vérifie si le hash du password saisie par l'utilisateur corresponds au hash de la base de donnees.
            if(password_verify( $oldPassword, $_SESSION['user']['passworduser']))
            {
                $hash_newpassword = password_hash ($newPassword ,PASSWORD_DEFAULT);
                $state = $this->sqlCommande->updatepassword($_SESSION['user']['iduser'],$hash_newpassword);
                if($state){
                    $_SESSION['user']['passworduser'] = $hash_newpassword;
                    $this->showUpdateProfil("Votre nouveau mot de passe a  été mis à jour");
                }
                else
                {
                    throw new ExceptionWithRedirect("Erreur impossible de modifier votre mot de passe !", 401, "mon-compte"); 
                }
            }
            else
            {
                $this->showUpdateProfil("Votre mot de passe n'est pas valide !");
            }    
            
        }
        else
        {
            $this->showUpdateProfil("Votre nouveau mot de passe et celui de confirmation ne sont pas identiques");
        } 
    }

    // Supprime le compte utilisateur actif
    function deleteMember()
    {
        $response = $this->sqlCommande->deleteAccount($_SESSION['user']['iduser']);
        // Détruit toutes les variables de session
        if($response)
        {
            $_SESSION = array();
            session_destroy();
            $this->getListMushrooms('1',false);
        }
        else
        {
            throw new ExceptionWithRedirect("Erreur lors de la suppression !", 401, "mon-compte"); 
        }
    }

    /*********************************************
    *    GESTION DES FICHES AJOUT EDIT DELETE
    **********************************************/
    // Chargement de la page ajouter une fiche
    public function ShowAddMushroom($dataText = "",$message = "")
    {
        $this->showAddOrUpdateFiche('Ajouter',"index.php?routing=addFiche",$dataText,"",$message);
    }

    // Chargement de la page modifier une fiche si l'utilisateur connecté en est proprietaire.
    public function showUpdateMushroom($idchamp,$message="")
    {
        $role = $_SESSION['user']['roleuser'];
        $iduser = $_SESSION['user']['iduser'];
        $fiche = $this->sqlCommande->getSheetMushroom($role,$iduser,$idchamp);
        if($fiche)
        {
            $images = [];
            foreach($fiche as $key => $value)
            {
                // recupere l'index de l'image
                $namePath = $fiche[$key]['idchamp'];
                if(isset($fiche[$key]['pathimg'])) $images[$key] = "/assets/images/photo-fullscreen/".$namePath."/minSize/".$fiche[$key]['pathimg'];
            }
            $this->showAddOrUpdateFiche("Editer","index.php?routing=editer-description",$fiche[0],$images,$message);   
        }
        else
        {
            throw new ExceptionWithRedirect("Impossible d'afficher cette page !", 404, "guide-des-champignons");
        }
    }

    // Rendu des pages ajouter ou modifier
    public function showAddOrUpdateFiche($title,$route,$dataText = "",$dataImage="",$message)
    {
        echo $this->render('comptes\addOrUpdateFiche.html.twig',
            ['title' => $title,
            'route' => $route,
            'dataText' => $dataText,
            'dataImage' => $dataImage,
            'message' => $message,
            'is_userConnect' =>  $this->get_value_session(),
            ]);        
    }

    // Ajoute la description et crée le dossier ajoute les photos
    public function AddMushroom($nomcommun,$nomlatin,$nomlocal,$chapeau,$gridRadios,$lames,$pied,$chair,$habitat,$remarques,$conso,$photos)
    {
        // Calculer si la capacité max d'upload n'est pas dépassé. 32000000
        $size_upload = $this->maxUpload($photos);
        if( $size_upload <  32000000)
        {
            // insert données dans la table champ
            $idFiche = $this->sqlCommande->AddMushroom($nomcommun,$nomlatin,$nomlocal,$chapeau,$gridRadios,$lames,$pied,$chair,$habitat,$remarques,$conso,$_SESSION['user']['iduser']);
            // Télécharge les photos
            $this->uploadPhoto($photos, $idFiche);
            header('location:index.php?routing=mon-compte');   
        }
        else
        {
            // recharge la page ajouter une fiche avec les données qui avaient été saisies hormis les images.
            $message = "Vous avez dépassé la capacité maximun d'upload : ".round(($size_upload/1000000), 2)." Mo, vérifer vos images et redimensionner si necessaire !";
            $dataText = [
                "communchamp" =>$nomcommun,
                'latinchamp' => $nomlatin,
                'localchamp' => $nomlocal,
                'chapeauchamp' => $chapeau,
                'typelameschamp' => $gridRadios,
                'lameschamp' => $lames,
                'piedchamp' => $pied,
                'chairchamp' => $chair,
                'habitatchamp' => $habitat,
                'remarqueschamp' => $remarques,
                'consochamp' => $conso,
                'photos' => $photos
            ];
            $this->ShowAddMushroom($dataText,$message);
        }   
    }

    // Mise à jour d'une fiche 
    public function updateMushroom($idchamp,$nomcommun,$nomlatin,$nomlocal,$photos,$conso,$chapeau,$gridRadios,$lames,$pied,$chair,$habitat,$remarques)
    {
        // Calculer si la capacité max d'upload n'est pas dépassé. 24000000
        $size_upload = $this->maxUpload($photos);
        if( $size_upload < 24000000)
        {
            $iduser = $_SESSION['user']['iduser'];
            // MAJ de la table champ
            $this->sqlCommande->updateMushroom($_SESSION['user']['iduser'],$idchamp,$nomcommun,$nomlatin,$nomlocal,$conso,$chapeau,$gridRadios,$lames,$pied,$chair,$habitat,$remarques);
            // Upload des photos
            $this->uploadPhoto($photos, $idchamp);
            header('location:index.php?routing=mon-compte'); 
        }
        else
        {
            $message = "Vous avez dépassé la capacité maximun d'upload : ".round(($size_upload/1000000), 2)." Mo, vérifer vos images et redimensionner si necessaire !";
            $this->showUpdateMushroom($idchamp,$message);
            exit;
        } 
    }

    // Calcul la poids totale des photos.
    private function maxUpload($photos)
    {
        if(isset($photos))
        {
            $maxUpload = 0;
            foreach($photos['error'] as $key => $value)
            {
                if( $value == 0)
                {
                    $maxUpload += $photos["size"][$key];
                }
            }
        }
        return $maxUpload;
    }

    // UPLOAD des photos. lors de l'ajout d'une nouvelle fiche
    public function uploadPhoto($photos,$idchamp)
    {
        $fiche = $this->sqlCommande->getImagesPerMushroom($idchamp);
        // Parcours le tableau photos
        foreach($photos['error'] as $key => $value)
        {
           // Si il n'a pas d'erreur
           if( $value == 0)
           {
               $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
               $filename = $photos["name"][$key];
               $filetype = $photos["type"][$key];
               $filesize = $photos["size"][$key];
               // Vérifie l'extension du fichier
               $ext = pathinfo($filename, PATHINFO_EXTENSION);
               if(!array_key_exists(strtolower($ext), $allowed))
               {
                $message = "Erreur : Veuillez sélectionner un format de fichier valide.";
                $this->showUpdateMushroom($idchamp,$message);
                die;   
               }
                
               // Vérifie le type MIME du fichier
               if(in_array($filetype, $allowed))
               {
                    // Le nom du dossier(id de la fiche) ou copier les photos 
                    $path = "./assets/images/photo-fullscreen/".$idchamp;
                    // Noms dossiers max_200(px)poue l'affichage des vignettes et max_1000(1000px) pour la description
                    $pathMinSize = $path."/minSize";
                    $pathMaxSize = $path."/maxSize";
                    // Nom du fichier photo uploader
                    $newFile = $photos["name"][$key];
                    // Cree le dossier et sous dossier si ils n'existent pas.
                    if (!file_exists($path))
                    {
                        $state = mkdir($path,0777,true);
                        $stateMin = mkdir($pathMinSize,0777,true);
                        $stateMax = mkdir($pathMaxSize,0777,true);
                    }
                    // Copie la photo redimensionne Mets à jour la table image.
                    if (file_exists($path) && file_exists($pathMinSize) && file_exists($pathMaxSize))
                    {
                        // Si le fichier n'existe pas je le télécharger et renvoie les données associées.
                        if(!file_exists($pathMinSize."/".$newFile) || !file_exists($pathMaxSize."/".$newFile)){
                            // Déplace un fichier téléchargé dans le sous-dossier max_1000
                            move_uploaded_file($photos["tmp_name"][$key],$pathMaxSize."/".$newFile);
                            // Copy le fichier dans le sous-dossier max_200
                            $is_copy = copy ( $pathMaxSize."/".$newFile , $pathMinSize."/".$newFile);
                            $resize = new ResizeImage();
                            // Redimensionne l'image max 1000 x 1000
                            $resize->resize($pathMaxSize."/".$newFile,1000);
                             // Redimensionne l'image max 200 x 200
                             $resize->resize($pathMinSize."/".$newFile,400);
                            // met a jour la BD sur un enregistrement 
                            if(isset($fiche[$key]))
                            {
                                // Supprime les anciens fichiers si ils existent
                                $oldPath = $fiche[$key]['pathimg'];
                                $deleteFileMinSize = $pathMinSize."/". $oldPath;
                                if(file_exists($deleteFileMinSize)) unlink($deleteFileMinSize);
                                $deleteFileMaxSize = $pathMaxSize."/". $oldPath;
                                if(file_exists($deleteFileMaxSize)) unlink($deleteFileMaxSize);
                                // id de l'image
                                $idimg = $fiche[$key]['idimg'];
                                $this->sqlCommande->updatePhoto($idimg,$newFile);
                            }
                            else
                            {
                                // ajoute un enregistrement
                                $data =['pathimg'=>$newFile,'idchamp'=>$idchamp];
                                $this->sqlCommande->insertPhoto($data);
                            }
                        }
                    } 
                    else
                    {
                        $message = "Erreur : Impossible d'uploader vos photos.";
                    }      
               }
           }
       }
    }

    //  Supprime une fiche descriptive
    public function deleteMushroomOfMember($idchamp,$currentPgFiche)
    {
        // Supprime la fiche et les enregistrements enfants avec la CONTRAINT ON DELETE CASCADE
        $result = $this->sqlCommande->deleteChampMembre($idchamp,$_SESSION['user']['iduser']);
        if($result)
        {
            try
            {
                $pathImage =  $_SERVER['DOCUMENT_ROOT']."/assets/images/photo-fullscreen/";
                array_map('unlink', glob($pathImage.$idchamp."/minSize/"."*.*"));
                array_map('unlink', glob($pathImage.$idchamp."/maxSize/"."*.*"));
                if(file_exists($pathImage.$idchamp."/minSize"))rmdir($pathImage.$idchamp."/minSize");
                if(file_exists($pathImage.$idchamp."/maxSize")) rmdir($pathImage.$idchamp."/maxSize");
                if(file_exists($pathImage.$idchamp)) rmdir($pathImage.$idchamp);
                header('location:index.php?routing=mon-compte&pageIndex='.$currentPgFiche);
            }
            catch (Exception $e) 
            {
                throw new ExceptionWithRedirect("Erreur impossible de supprimer les photos !", 401, "mon-compte");
            }
        }
        else
        {
            throw new ExceptionWithRedirect("Erreur lors de la suppression !", 404, "mon-compte");
        }
    }

}