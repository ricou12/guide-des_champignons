<?php

require_once 'vendor/autoload.php';
// RENDU DU TEMPLATE
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
// COMPILATION ET DEBUGGER
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__.'/compilation_cache',
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// FONCTIONS PERSONALISES TWIG
require_once ("twig/fctTwig.php");

// MODELE ET GESTION D'ERREURS
require_once ("services/ExceptionWithRedirect.php");
require_once ("modele/sqlCommand.php"); 


// CONTROLLER
require_once ("controller/AppController.php");
require_once ("controller/LoginController.php");
require_once ("controller/AdminController.php");
require_once ("controller/MemberController.php");

// CONNEXION A LA BASE DE DONNEE
try {
    $sqlCommande = new MyComponentsSql();
    $connect = $sqlCommande->connectDataBase('encyclopedie_champignons');
    
} catch (Exception $e) {
    die('Erreur SQL : ' . $e->getMessage());
}

// INSTANCIE LES CLASSES ENFANTS DE BaseController
$appController = new AppController($twig, $sqlCommande);
$loginController = new LoginController($twig, $sqlCommande);
$adminController = new AdminController($twig, $sqlCommande);
$memberController = new MemberController($twig, $sqlCommande);
session_start();
 
// ROUTAGE PAR DEFAULT (premier chargement)
$page = 'portail';

if(isset($_GET['routing'])){
   $page =  $_GET['routing'];
}

// ROUTING
try
{
    switch ($page) 
    {
        /*******************************************
         *          ACCES PUBLIC
        ********************************************/
        // Rendu de la page d'accueil.
        case 'portail':
            $appController->home();
        break;

        // Rendu page liste des vignettes.
        case 'fiches':
            $page = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : '1';
            $appController->listeFiches($page,isset($_GET['allFiches']));
        break;

        // Rendu page fiche descriptive session public
        case 'description':
            if(isset($_GET['idchamp']) && !empty($_GET['idchamp']))
            {
                $appController->getFiche('Public','',$_GET['idchamp']);
            }
            else
            {
                throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "fiches");
            }
        break;

        // Rendu page fiche descriptive session privée.
         case 'fiche-descriptive':
            if(isset($_GET['idchamp']) && !empty($_GET['idchamp']))
            {
                // Si il y a un id utilisateur
                if($appController->get_value_session())
                {
                    // Je récupère le role et id de l'utilisateur
                    $role = $_SESSION['user']['roleuser'];
                    $idUser = $_SESSION['user']['iduser'];
                    $appController->getFiche($role,$idUser,$_GET['idchamp']);
                }
                else
                {
                    $appController->getFiche('Public','',$_GET['idchamp']);
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "fiches");
            }
        break;

        /*******************************************
         *          ACCES PRIVEE
        ********************************************/
        // Rendu de la page ajouter une fiche.
        case 'ajouter-fiche':
             if($appController->get_value_session())
             {
                $memberController->ShowAddFiche();
             }
             else
             {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource  !", 401, "connexion");
             }
            
        break;

        // Ajouter une fiche.
        case 'addFiche':
            if(isset($_POST['nomcommun']) && !empty($_POST['nomcommun']))
            {
                $memberController->addFiche($_POST['nomcommun'],$_POST['nomlatin'],$_POST['nomlocal'],$_POST['chapeau'],$_POST['gridRadios'],$_POST['lames'],$_POST['pied'],$_POST['chair'],$_POST['habitat'],$_POST['remarques'],$_POST['conso'],$_FILES['photo']);  
            }
            else
            {
                throw new ExceptionWithRedirect("Erreur, assurez vous d'avoir remplit le champ Nom Commun et que vous ne dépassez pas la capacité max d'upload !", 401, "portail");
            } 
        break;

        // Rendu de la page MAJ d'une fiche.
        case 'update-fiche':
            if (isset($_GET['idChamp']) && !empty($_GET['idChamp']))
            {
                $memberController->showUpdateFiche($_GET['idChamp']);
            }
        break;

        // MAJ d'une fiche
        case 'updateFiche':
            if(isset($_POST['nomcommun']) && !empty($_POST['nomcommun']) && isset($_POST['idchamp']) && !empty($_POST['idchamp']))
            {
                $memberController->updateFiche($_POST['idchamp'],$_POST['nomcommun'],$_POST['nomlatin'],$_POST['nomlocal'],$_FILES['photo'],$_POST['conso'],$_POST['chapeau'],$_POST['gridRadios'],$_POST['lames'],$_POST['pied'],$_POST['chair'],$_POST['habitat'],$_POST['remarques']);
            }
            
        break;

        // delete fiche en tant que membre
        case 'del-champ-mb':
            if(isset($_GET['idFich']) && !empty($_GET['idFich']))
            {
                $memberController->deleteChampMember($_GET['idFich'],$_GET['currentPgFiche']);
            }    
        break;

        // delete fiche en tant que administrateur
        case 'del-champ-ad':
            if(isset($_GET['idFich']) && !empty($_GET['idFich']))
            {
                $adminController->deleteChampAdmin($_GET['idFich'],$_GET['currentPgFiche']);
            }    
        break;

        /*******************************************
         *          GESTION DE COMPTES
        ********************************************/
        // Rendu de la page créer un compte
        case 'inscription':
            if($appController->get_value_session())
            {
               header("location:index.php?routing=mon-compte");
            }
            else
            {
                $loginController->showRegister(); 
            }
        break;

        // Crée un compte
        case 'createRegister':
            // Vérifie si les champ sont remplis
            if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']))
            {
                $loginController->createRegister($_POST['pseudo'],$_POST['password']);   
            }
            else
            {
                // si les champs sont vides et que l'utilisateur n'a pas ouvert de session.
                if(!$appController->get_value_session())
                {
                    $loginController->showRegister();
                }
                else
                {
                     throw new ExceptionWithRedirect("Vous ne possédez pas les droits pour supprimer ce compte  !", 401, "mon-compte"); 
                }
            }
        break;

        // Rendu de la page se connecter
        case 'connexion':
            if($appController->get_value_session())
            {
                 header('location:index.php?routing=mon-compte');
             }
             else
             {
                $loginController->showConnexion(); 
             }          
        break;

        // Se connecte
        case 'verifie-connection':
            if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']))
            {
             $loginController->LoginVerify($_POST['pseudo'],$_POST['password']);   
            }
            else
            {
                header("location:index.php?routing=connexion");
            }
        break;

        // se déconnecter (detruit une session)
        case 'deconnexion':
            $loginController->stop_session();
        break;

        // rendu de la page mon compte admin ou member
        case 'mon-compte':
            if($appController->get_value_session())
            {
                    switch ($_SESSION['user']['roleuser'])
                    {
                        case 'Administrateur' :
                            $pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : '1';
                            $pageUserIndex = isset($_GET['pageUserIndex']) ? $_GET['pageUserIndex'] : '1';
                            $adminController->showCompteAdmin($pageIndex,$pageUserIndex,isset($_GET['allFiche']));
                        break;
                        case 'Membre' :
                            $pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : '1';
                            $memberController->showCompteMember($pageIndex,$_SESSION['user']['iduser'],isset($_GET['allFiche']));
                        break;             
                    }
                }
                else
                {
                    header('location: index.php?routing=fiches');
                }
        break;

        // Supprimer son compte en tant que membre
        case 'delete-member':
             if(isset($_SESSION['user']) && !empty($_SESSION['user']))
            {
                $memberController->deleteMember();
            }
            else
            {
                 throw new ExceptionWithRedirect("Vous ne possédez pas les droits pour supprimer ce compte  !", 401, "fiches"); 
            }
        break;

        // Supprimer un compte en tant qu'administrateur
        case 'deleteAccount':
            if ( $appController->get_value_session())
            {
                if ( isset($_GET['idUser']) && !empty($_GET['idUser']) )
                {
                    if ( $_SESSION['user']['roleuser'] === "Administrateur" )
                    {
                        $adminController->deleteAccount( $_GET['idUser'],$_GET['currentPgUser'],$_GET['currentPgFiche'] );
                    }
                    else
                    {
                        throw new ExceptionWithRedirect("Vous ne possédez pas les droits pour supprimer ce compte  !", 401, "portail");  
                    }
                }
                else
                {
                    header('location:index.php?routing=mon-compte');
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Vous devez être connecté  !", 401, "portail");  
            }
        break;
        
        // rendu page modifier mon profil
        case 'mon-profil':
            if($appController->get_value_session())
            {
                $memberController->showUpdateProfil();
            }
            else
            {
                header('location:index.php?routing=verifie-connection');
            }
        break;

        // Modifier son profil
        case 'update-profil':
            if($appController->get_value_session())
            {
                if (isset($_POST['pseudo']) && !empty($_POST['pseudo']))
                {
                    $memberController->updateProfil($_POST['pseudo'],$_POST['lastname'],$_POST['firstname']);
                }
                else
                {
                    header('location:index.php?routing=mon-profil');
                }  
            }
            else
            {
                throw new ExceptionWithRedirect("Vous devez vous connecter pour accèder à cette page  !", 404, "portail");
            }
        break;

        // Changer de mot de passe
        case 'update-password':
            if($appController->get_value_session())
            {
                if (isset($_POST['newPassword']) && isset($_POST['oldPassword']) && isset($_POST['confirmNewpassword']))
                {
                    $memberController->updatePassword($_POST['oldPassword'],$_POST['newPassword'],$_POST['confirmNewpassword']);
                }
                else
                {
                    header('location:index.php?routing=mon-profil');
                }  
            }
            else
            {
                throw new ExceptionWithRedirect("Vous devez vous connecter pour accèder à cette page  !", 404, "portail");
            }
        break;

        // Masquer ou afficher au public
        case 'autoriser-fiche':
           if(isset($_GET['idFiche']) && !empty($_GET['idFiche']))
            {
                $adminController->autoriser($_GET['idFiche'],$_GET['currentPgFiche']);
            }
            else
            {
                throw new ExceptionWithRedirect("Désolé, cette page n'existe pas !", 404, "portail");
            }  
        break;

        // Si aucune page trouvée alors erreur 404
        default:
            throw new ExceptionWithRedirect("Désolé, cette page n'existe pas !", 404, "portail");
        
        break;
    }
}
catch(Exception $e)
{
    $appController->handleError($e);
}