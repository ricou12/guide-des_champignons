<?php

require_once (__DIR__.'/../vendor/autoload.php');
// RENDU DU TEMPLATE
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');
// COMPILATION ET DEBUGGER
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__.'/../compilation_cache',
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// FONCTIONS PERSONALISES TWIG
require_once ( __DIR__.'/../twig/fctTwig.php');

// MODELE ET GESTION D'ERREURS
require_once ( __DIR__.'/../services/ExceptionWithRedirect.php');
require_once ( __DIR__.'/../modele/sqlCommand.php'); 


// CONTROLLER
require_once ( __DIR__.'/../controller/AppController.php');
require_once ( __DIR__.'/../controller/LoginController.php');
require_once ( __DIR__.'/../controller/AdminController.php');
require_once ( __DIR__.'/../controller/MemberController.php');

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
if(isset($_GET['routing'])) {
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
        case 'guide-des-champignons':
            $page = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : '1';
            if(is_numeric($page))
            {
            $appController->getListMushrooms($page,isset($_GET['allFiches']));
            }
            else
            {
                throw new ExceptionWithRedirect("Erreur impossible de charger la page !", 404, "guide-des-champignons");
            }
        break;

        // Rendu page fiche descriptive session public
        case 'description':
            if(isset($_GET['idchamp']) && !empty($_GET['idchamp']) && is_numeric($_GET['idchamp']))
            {
                $appController->getSheetMushroom('Public','',$_GET['idchamp']);
            }
            else
            {
                throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "guide-des-champignons");
            }
        break;

        // Rendu page fiche descriptive session privée.
         case 'description-privee':
            if(isset($_GET['idchamp']) && !empty($_GET['idchamp']) && is_numeric($_GET['idchamp']))
            {
                // Si il y a un id utilisateur
                if($appController->get_value_session())
                {
                    // Je récupère le role et id de l'utilisateur
                    $role = $_SESSION['user']['roleuser'];
                    $idUser = $_SESSION['user']['iduser'];
                    $appController->getSheetMushroom($role,$idUser,$_GET['idchamp']);
                }
                else
                {
                    $appController->getSheetMushroom('Public','',$_GET['idchamp']);
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Cette page n'existe pas !", 404, "guide-des-champignons");
            }
        break;

        /*******************************************
         *          ACCES PRIVEE
        ********************************************/
        // Rendu de la page ajouter une fiche.
        case 'ajouter-une-fiche-descriptive':
             if($appController->get_value_session())
             {
                $memberController->ShowAddMushroom();
             }
             else
             {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource  !", 401, "se-connecter");
             }
            
        break;

        // Ajouter une fiche.
        case 'addFiche':
            if($appController->get_value_session())
            {
                if(isset($_POST['nomcommun']) && !empty($_POST['nomcommun']))
                {
                    $memberController->AddMushroom($_POST['nomcommun'],$_POST['nomlatin'],$_POST['nomlocal'],$_POST['chapeau'],$_POST['gridRadios'],$_POST['lames'],$_POST['pied'],$_POST['chair'],$_POST['habitat'],$_POST['remarques'],$_POST['conso'],$_FILES['photo']);  
                }
                else
                {
                    throw new ExceptionWithRedirect("Erreur, assurez vous d'avoir remplit le champ Nom Commun et que vous ne dépassez pas la capacité max d'upload !", 401, "portail");
                } 
            }
            else
            {
               throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource  !", 401, "se-connecter");
            }
        break;

        // Rendu de la page MAJ d'une fiche.
        case 'editer-une-fiche-descriptive':
            if($appController->get_value_session())
            {
                if (isset($_GET['idChamp']) && !empty($_GET['idChamp']) && is_numeric($_GET['idChamp']))
                {
                    $memberController->showUpdateMushroom($_GET['idChamp']);
                }
            }
            else
            {
               throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource  !", 401, "se-connecter");
            }
        break;

        // MAJ d'une fiche
        case 'editer-description':
            // Verifie si l'utilisateur est connecté et envoi son id de compte afin de déterminer qu'il soit propriétaire de la fiche.
            if( $appController->get_value_session())
            {
                if(isset($_POST['nomcommun']) && !empty($_POST['nomcommun']) && isset($_POST['idchamp']) && !empty($_POST['idchamp']))
                {
                    $memberController->updateMushroom($_POST['idchamp'],$_POST['nomcommun'],$_POST['nomlatin'],$_POST['nomlocal'],$_FILES['photo'],$_POST['conso'],$_POST['chapeau'],$_POST['gridRadios'],$_POST['lames'],$_POST['pied'],$_POST['chair'],$_POST['habitat'],$_POST['remarques']);
                }
                else
                {
                    $memberController->showUpdateMushroom($_POST['idchamp'],"Veuillez remplir le champ nom commun"); 
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            } 
        break;

        // delete fiche en tant que membre
        case 'supprimer-ma-fiche-descriptive':
            // Verifie si l'utilisateur est connecté et envoi son id de compte afin de déterminer qu'il soit propriétaire de la fiche.
            if( $appController->get_value_session())
                {
                if(isset($_GET['idFich']) && !empty($_GET['idFich']) && is_numeric($_GET['idFich']))
                {
                    $memberController->deleteMushroomOfMember($_GET['idFich'],$_GET['currentPgFiche']);
                }  
            }
            else
            {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            }   
        break;

        // delete fiche en tant que administrateur
        case 'supprimer-description':
            if( $appController->get_value_session() )
            {
                if($_SESSION['user']['roleuser'] == 'Administrateur')
                {
                    if(isset($_GET['idFich']) && !empty($_GET['idFich']) && is_numeric($_GET['idFich']))
                    {
                        $adminController->deleteMushroomOfAdmin($_GET['idFich'],$_GET['currentPgFiche']);
                    }
                }
                else
                {
                    header("location:index.php?routing=mon-compte".$_GET['currentPgFiche']);
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            }     
        break;

        /*******************************************
         *          GESTION DE COMPTES
        ********************************************/
        // Rendu de la page créer un compte
        case 'creer-mon-compte':
            if($appController->get_value_session())
            {
               header("location:index.php?routing=mon-compte");
            }
            else
            {
                $loginController->showRegister(); 
            }
        break;

        // Créer un compte
        case 'creation-du-compte':
            if($appController->get_value_session())
            {
               header("location:index.php?routing=mon-compte");
            }
            else
            {
                // Vérifie si les champ sont remplis
                if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']))
                {
                    $loginController->createRegister($_POST['pseudo'],$_POST['password']);   
                }
                else
                {
                    // si les champs sont vides
                    $loginController->showRegister();   
                }
            }
        break;

        // Rendu de la page se connecter
        case 'se-connecter':
            if($appController->get_value_session())
            {
                 header('location:index.php?routing=mon-compte');
             }
             else
             {
                $loginController->showLogIn(); 
             }          
        break;

        // Se connecte
        case 'connexion':
            if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']))
            {
             $loginController->loginVerify($_POST['pseudo'],$_POST['password']);   
            }
            else
            {
                header("location:/se-connecter");
            }
        break;

        // se déconnecter (detruit une session)
        case 'deconnexion':
            if($appController->get_value_session())
            {
                $loginController->stop_session();
            }
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
                        if( is_numeric($pageIndex) && is_numeric($pageUserIndex) )
                        {
                             $adminController->showAccountAdmin($pageIndex,$pageUserIndex,isset($_GET['allFiche']));
                        }
                        else
                        {
                            throw new ExceptionWithRedirect("Une erreur c'est produite !", 404, "portail");
                        } 
                    break;
                    
                    case 'Membre' :
                        $pageIndex = isset($_GET['pageIndex']) ? $_GET['pageIndex'] : '1';
                        if( is_numeric($pageIndex))
                        {
                            $memberController->showAccountMember($pageIndex,$_SESSION['user']['iduser'],isset($_GET['allFiche']));
                        }
                        else
                        {
                            throw new ExceptionWithRedirect("Une erreur c'est produite !", 404, "portail");
                        } 
                    break;

                    // Si aucune page trouvée alors erreur 404
                    default:
                        throw new ExceptionWithRedirect("Une erreur c'est produite !", 404, "portail");
                    break;               
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            }
        break;

        // Supprimer son compte en tant que membre
        case 'me-desincrire':
             if($appController->get_value_session())
            {
                $memberController->deleteMember();
            }
            else
            {
                 throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "guide-des-champignons"); 
            }
        break;

        // Supprimer un compte en tant qu'administrateur
        case 'suppression-de-compte':
            if ( $appController->get_value_session())
            {
                if ( $_SESSION['user']['roleuser'] === "Administrateur" )
                {
                    if ( isset($_GET['idUser']) && !empty($_GET['idUser']) && is_numeric($_GET['idUser']) )
                    {
                        $adminController->deleteAccount( $_GET['idUser'],$_GET['currentPgUser'],$_GET['currentPgFiche'] );
                    }
                    else
                    {
                        header('location:index.php?routing=mon-compte');
                    }
                }
                else
                {
                    throw new ExceptionWithRedirect("Vous ne possédez pas les droits pour supprimer ce compte  !", 401, "mon-compte");  
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");  
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
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            }
        break;

        // Modifier son profil
        case 'editer-mon-profil':
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
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            }
        break;

        // Changer de mot de passe
        case 'editer-mon-mot-de-passe':
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
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
            }
        break;

        // Masquer ou afficher au public
        case 'autoriser-fiche':
            if($appController->get_value_session())
            {
                if( $_SESSION['user']['roleuser'] === 'Administrateur' )
                {
                    if(isset($_GET['idFiche']) && !empty($_GET['idFiche']) && is_numeric($_GET['idFiche']))
                    {
                        $adminController->authorizePublication($_GET['idFiche'],$_GET['currentPgFiche']);
                    }
                    else
                    {
                        throw new ExceptionWithRedirect("Désolé, cette page n'existe pas !", 404, "portail");
                    }  
                }
                else
                {
                    throw new ExceptionWithRedirect("Désolé, cette section est réservé à l'administrateur !", 404, "portail");
                }
            }
            else
            {
                throw new ExceptionWithRedirect("Une authentification est nécessaire pour accéder à la ressource !", 401, "se-connecter");
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