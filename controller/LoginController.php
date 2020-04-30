<?php
require_once(__DIR__.'/AppController.php');

class LoginController extends AppController {

    // Creation d'un compte
    function createRegister($pseudo,$password)
    {
        $response = $this->sqlCommande->createLogin($pseudo,$password);
        // Si le compte a été crée.
        if($response['success'])
        {
            $_SESSION['user'] = $response['iduser'];
            // Charge la page liste des guide-des-champignons
            $list = $this->sqlCommande->listeFiches('1',false);
            header('location:index.php?routing=mon-compte');
        }
        else
        {
            $this->showRegister($response['message']);
        }
    }

    // rendu page login
    function showRegister($message="")
    {
        echo $this->render('logger/register.html.twig', 
            ['title' => 'Inscription',
            'erreur' => $message
        ]);
    }

    // Rendu de la page se connecter
    function showConnexion()
    {
        echo $this->render('logger/login.html.twig', ['title' => 'Connexion']);
    }

    // Vérifie si le compte existe
    function loginVerify($pseudo,$password)
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
            echo $this->render('logger/login.html.twig', ['title' => 'Connexion',
                'erreur' => $response['message']
            ]);
        }
    }

    // Se deconnecter
    public function stop_session()
    {
        // Détruit toutes les variables de session
        $_SESSION = array();
        session_destroy();
        $this->listeFiches('1',false);
    }
}