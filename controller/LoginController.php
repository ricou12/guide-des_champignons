<?php
require_once(__DIR__.'/AppController.php');

class LoginController extends AppController {
    // Creation d'un compte
    function createAnAccount($pseudo,$password)
    {
        $valuePseudo = "";
        $messageErrorPseudo = "";
        $messageErrorPassword = "";
        $isOkPseudo = false;
        $IsOkPassword = false;
        // Verifie que tous les champs soit renseignés et valides.
        // Pseudo
        if(empty($pseudo)){
            $messageErrorPseudo = "Merci de renseigner votre pseudo";
        }
        else
        {
            $valuePseudo = $pseudo;
             // Vérifie si le compte existe dans la base de données
            if($this->sqlCommande->isExistPseudo($pseudo))
            {
                $messageErrorPseudo = "Désolé ! ce pseudo est utilisé. Veuillez en choisir un autre.";
            } 
            else
            {
                $isOkPseudo = true;
            } 
        }
        // Password
        if(empty($password)){
            $messageErrorPassword = "Merci de saisir un mot de passe";
        }
        else
        {
            // Vérifie la conformité du mot de passe
            if(preg_match('#(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}#', $password))
            {
                $IsOkPassword = true;
            }
            else
            {
                $messageErrorPassword = "Votre mot de passe n'est pas conforme.";
            }
        }

        // Si le pseudo et password sont conforme, créér le compte.
        if( $isOkPseudo && $IsOkPassword)
        {
            // Création du compte
            $response = $this->sqlCommande->createAccount($pseudo,$password);
            if($response['profilUser'])
            {
                $_SESSION['user'] = $response['profilUser'];
                // Redirection vers la page membre
                header('location:index.php?routing=mon-compte');
            }
            else
            {
                 // Si bug pendant l'enregistrement
                 throw new ExceptionWithRedirect("Une erreur c'est produite !", 404, "portail");
            }
        }
        else
        {
            // Affiche les messages dans le formulaire inscription
            $this->showRegister($messageErrorPseudo,$messageErrorPassword,"",$valuePseudo); 
        }

    }


    // rendu page inscription
    function showRegister($infoPseudo="",$infoPassword="",$message="",$pseudo="")
    {
        echo $this->render('logger/register.html.twig', 
            [
            'name' => 'Inscription',
            'title' => 'S\'inscrire',
            'infoPseudo' => $infoPseudo,
            'infoPassword' => $infoPassword,
            'erreur' => $message,
            'pseudo' => $pseudo
           ]
        );
    }

    // Rendu de la page se connecter
    function showLogIn()
    {
        echo $this->render('logger/login.html.twig',
            [
            'name' => 'Connexion',
            'title' => 'Se connecter'
            ]
        );
    }

    // Vérifie si le compte existe
    function loginVerify($pseudo,$password)
    {
        // Verification de la conformité du mot de passe
        //  6 caractères mini, au moins un chiffre une lettre majuscule et minuscule
        if(preg_match('#(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}#', $password))
        {
            $response = $this->sqlCommande->get_login($pseudo,$password);
            // Si renvoi false alors traite l'erreur
            if($response['success'])
            {
                // Cree une session pour l'utilisateur 
                // session_start();
                $_SESSION['user'] = $response['user'];
                header('location:index.php?routing=mon-compte');
            }
            else
            {
                echo $this->render('logger/login.html.twig',
                    [
                    'name' => 'Connexion',
                    'title' => 'Se connecter',
                    'erreur' => $response['message']
                ]);
            }
        }
        else
        {
            echo $this->render('logger/login.html.twig',
            [
            'name' => 'Connexion',
            'title' => 'Se connecter',
            'erreur' => 'Le mot de passe n\'est pas conforme'
        ]); 
        }
    }

    // Se deconnecter
    public function stop_session()
    {
        // Détruit toutes les variables de session
        $_SESSION = array();
        session_destroy();
        $this->getListMushrooms('1',false);
    }
}