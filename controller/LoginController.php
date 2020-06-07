<?php
require_once(__DIR__.'/AppController.php');

class LoginController extends AppController {
    // TODO verifier conformité mot de passe et pseudo 
    // Creation d'un compte
    function createAnAccount($pseudo,$password)
    {
        // Verification de la conformité du mot de passe
        //  6 caractères mini, au moins un chiffre une lettre majuscule et minuscule
        if(preg_match('#(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}#', $password))
        {
            $response = $this->sqlCommande->createAccount($pseudo,$password);
            // Si le compte a été crée.
            if($response['success'])
            {
                $_SESSION['user'] = $response['iduser'];
                // Charge la page membre
                header('location:index.php?routing=mon-compte');
            }
            else
            {
                // recharge la page s'inscrire et affiche le message
                $this->showRegister($response['message']);
            }
        }
        else
        {
            // recharge la page s'inscrire et affiche le message
            $this->showRegister('Le mot de passe n\'est pas conforme.');
        }
    }


    // rendu page inscription
    //TODO afficher les messages 'infoPseudo' =>$infoPseudo,'infoMotPasse' =>$infoMtPasse,
    function showRegister($message="")
    {
        echo $this->render('logger/register.html.twig', 
            [
            'name' => 'Inscription',
            'title' => 'S\'inscrire',
            'erreur' => $message
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