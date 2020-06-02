<?php

class MyComponentsSql {
    private $_dataBase;
    // CONNECTION A LA BASE DE DONNEES ET TRAITEMENT DES ERREURS
    public function connectDataBase($myDB)
    {
    //    try {
            // CONNECTION A MYSQL
            $this->_dataBase = new PDO('mysql:host=localhost:3306;dbname='.$myDB.';charset=utf8', 'root', '');
            // ACTIVE LA GESTION DES ERREURS : REMONTE DANS LE CODE LES EXCEPTIONS SQL 
            // $this->_dataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        // }
        // catch (Exception $e) {
	    //     // EN CAS D'ERREUR ON AFFICHE UN MESSAGE ET ON ARRETE TOUT
        //     die('Erreur : ' . $e->getMessage());
        // }
    }

    /****************************************************
                  LISTE ET FICHES INDIVIDUELLES
    *****************************************************/
    // Récupère toute les fiches signalétique et les images par jointures.
    public function getListMushrooms($page,$isAllFiche)
    {
        $query = $this->_dataBase->prepare('SELECT COUNT(*) FROM champ WHERE champ.autoriserchamp = 1');
        $query->execute();
        $result = $query->fetch();
        $query->closeCursor();
        $nbreperpage = 0;
        $nbre_pages = 1;
        if($isAllFiche) {
            $nbreperpage =  $result[0];
            $offset = 0;
        } else {
            $nbreperpage = 10;
            $nbre_pages= ceil(intval($result[0]) / intval($nbreperpage));
            $offset = ( $page * $nbreperpage ) - $nbreperpage;
        }
        $query = $this->_dataBase->prepare('SELECT champ.idchamp,autoriserchamp,datechamp,communchamp,latinchamp,localchamp,chapeauchamp,typelameschamp,lameschamp,piedchamp,chairchamp,habitatchamp,remarqueschamp,consochamp,iduser,pathimg FROM champ LEFT OUTER JOIN images ON champ.idchamp = images.idchamp WHERE champ.autoriserchamp = 1 GROUP BY champ.idchamp ORDER BY champ.communchamp LIMIT :max OFFSET :offset ');
        $query->bindParam(':max',$nbreperpage, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return ['nbrePage' =>$nbre_pages,'listFiches' => $result];
    }

    // Récupère une fiche signalétique et les images par jointures.
    public function getSheetMushroom($role,$iduser,$idchamp)
    {
        // c'est le role qui détermine les différences dans la requete
        $sql;
        switch ($role)
        {
            case 'Administrateur' :
                $sql = "";
            break;
            case 'Membre' :
                $sql = " && champ.iduser = :iduser";
            break;
            case 'Public' :
                $sql = " && champ.autoriserchamp = 1";
            break;
        }
        $query = $this->_dataBase->prepare('SELECT champ.idchamp,autoriserchamp,datechamp,communchamp,latinchamp,localchamp,chapeauchamp,typelameschamp,lameschamp,piedchamp,chairchamp,habitatchamp,remarqueschamp,consochamp,champ.iduser,pathimg FROM champ LEFT OUTER JOIN images ON champ.idchamp = images.idchamp  WHERE champ.idchamp = :idchamp'.$sql);
        switch ($role)
        {
            case 'Membre' :
                $query->bindParam(':iduser', $iduser);
            break;
        }
        $query->bindParam(':idchamp', $idchamp);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return  $result;
    }

    /****************************************************
                  GESTION DU LOGIN
    *****************************************************/
    public function isExistPseudo($pseudo){
        // Je vérifie si le compte existe avec le pseudo.
        $query = $this->_dataBase->prepare('SELECT * FROM utilisateurs where pseudouser = :pseudo');
        $query->bindParam(':pseudo', $pseudo);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $result;
    }


    public function createAccount($pseudo,$password)
    {
        $is_exist_pseudo = $this->isExistPseudo($pseudo);
        if($is_exist_pseudo)
        {
           return ['success' => false,'message' => "Désolé ! ce compte existe déja, choisissez un autre pseudo !"]; 
        }
        else
        {
        // Hachage du password.
        $hash_password = password_hash ($password ,PASSWORD_DEFAULT);
        $query = $this->_dataBase->prepare('INSERT INTO utilisateurs (pseudouser,passworduser) VALUES (:pseudouser, :passworduser)');
        $query->bindParam(':pseudouser', $pseudo);
        $query->bindParam(':passworduser', $hash_password);
        $query->execute();
        $query->closeCursor();
        $id = $this->_dataBase->lastInsertId();
        $query = $this->_dataBase->prepare('SELECT * FROM utilisateurs where iduser = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return ['success' => true,'message' => "Merci d'avoir crée votre compte !",'iduser' => $result]; 
        }
    }

    
    public function get_login($pseudo,$password)
    {
        $is_exist_pseudo = $this->isExistPseudo($pseudo);
        if($is_exist_pseudo)
        {
            // Hachage du password saisie par l'utilisateur.
            $hash_password = password_hash ($password ,PASSWORD_DEFAULT);
            // Vérifie si le hash du password saisie par l'utilisateur corresponds au hash de la base de donnees.
            if(password_verify( $password, $is_exist_pseudo['passworduser']))
            {
                // Renvoie le profil de l'utilisateur
                return['success' => true, 'user' => $is_exist_pseudo];
            }
            else
            {
                return ['success' => false,'message' => "Erreur de mot saisie dans le mot de passe, veuillez réessayer."]; 
            }
        }
        else
        {
            return ['success' => false, 'message' =>  "Désolé ! vous n'êtes pas inscrit."]; 
        }
    }

    public function deleteAccount($idUser)
    {
        $query = $this->_dataBase->prepare('DELETE FROM utilisateurs WHERE utilisateurs.iduser = :idUser');
        $query->bindParam(':idUser', $idUser);
        $result = $query->execute();
        $query->closeCursor();
        return $result;
    }

    public function updateProfil($iduser,$pseudouser,$nomuser,$prenomuser)
    {
        $is_exist_pseudo = $this->isExistPseudo($pseudouser);
        // Si le pseudo existe et n'est pas celui de l'utilisateur alors on ne modifie pas le profil
        if($is_exist_pseudo && $is_exist_pseudo['iduser'] != $iduser)
        {
            return ['success' => false,'is_exist_other_speudo' => true];
        }
        // si le speudo n'existe pas (l'user a modifié son pseudo)
        // ou si l'utlisateur ne l'a pas modifié alors modifie le profil
        if(!$is_exist_pseudo || $is_exist_pseudo['iduser'] == $iduser )
        {
            $query = $this->_dataBase->prepare('UPDATE utilisateurs SET pseudouser=:pseudouser,nomuser=:nomuser,prenomuser=:prenomuser WHERE iduser=:iduser');
            $query->bindParam(':iduser',$iduser, PDO::PARAM_INT);
            $query->bindParam(':pseudouser',$pseudouser);
            $query->bindParam(':nomuser',$nomuser);
            $query->bindParam(':prenomuser', $prenomuser); 
            $result = $query->execute();
            $query->closeCursor();
            return  ['success' => $result,'is_exist_other_speudo' => false];
        }
    }

    public function updatepassword($iduser,$newPassword)
    {
        $query = $this->_dataBase->prepare('UPDATE utilisateurs SET passworduser = :passworduser WHERE iduser = :iduser');
        $query->bindParam(':iduser',$iduser, PDO::PARAM_INT);
        $query->bindParam(':passworduser',$newPassword);
        $result = $query->execute();
        $query->closeCursor();
        return $result;
    }

    /****************************************************
                  PAGES D'ADMINISTRATION DES COMPTES
    *****************************************************/
    // Dresse la liste des comptes
    public function listUsers($page)
    {
        $query = $this->_dataBase->prepare('select count(*) from utilisateurs');
        $query->execute();
        $result = $query->fetch();
        $query->closeCursor();
        $nbreperpage = 10;
        $nbre_pages= ceil(intval($result[0]) / $nbreperpage);
        $offset = ( $page * $nbreperpage ) - $nbreperpage;
        $query = $this->_dataBase->prepare('SELECT * FROM utilisateurs LIMIT :max OFFSET :offset');
        $query->bindParam(':max',$nbreperpage, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT); 
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return [$nbre_pages,$result];
    }

    //  Affiche la liste des fiches descriptive par utilisateurs.
    public function listMushroomsPerUser($page,$idUser="", $allFiche )
    {
        $sql ="";
        $query="";
        // si c'est un membre id de l'utilisateur pour recupèrer ses fiches perso
        if($idUser != ""){
             $query = $this->_dataBase->prepare('select count(*) from champ where champ.iduser = :iduser');
             $query->bindParam(':iduser',$idUser, PDO::PARAM_INT);
             $sql = 'WHERE utilisateurs.iduser =:idUser';
        }
        else
        {
            $query = $this->_dataBase->prepare('select count(*) from champ');
        }
        $query->execute();
        $result = $query->fetch();
        $query->closeCursor();
        $nbreperpage = 0;
        $nbre_pages = 1;
        if($allFiche) {
            $nbreperpage =  $result[0];
            $offset = 0;
        } else {
            $nbreperpage = 5;
            $nbre_pages= ceil(intval($result[0]) / intval($nbreperpage));
            $offset = ( $page * $nbreperpage ) - $nbreperpage;
        }
        $query = $this->_dataBase->prepare('SELECT pseudouser,champ.idchamp,communchamp,autoriserchamp,datechamp,pathimg FROM utilisateurs RIGHT JOIN champ ON utilisateurs.iduser = champ.iduser LEFT JOIN images ON champ.idchamp = images.idchamp '.$sql.' GROUP BY champ.idchamp ORDER BY champ.communchamp LIMIT :max OFFSET :offset');
        // si c'est un membre recupere que ses fiches
        if($idUser != "") $query->bindParam(':idUser',$idUser, PDO::PARAM_INT);
        $query->bindParam(':max',$nbreperpage, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return  [$nbre_pages,$result];
    }

    // Ajoute une fiche pour un utilisateur via son id stocké dans la variable de session
    public function AddMushroom($nomcommun,$nomlatin,$nomlocal,$chapeau,$gridRadios,$lames,$pied,$chair,$habitat,$remarques,$conso,$iduser)
    {
         $query = $this->_dataBase->prepare('INSERT INTO champ (communchamp,latinchamp,localchamp,chapeauchamp,
            typelameschamp,lameschamp,piedchamp,chairchamp,habitatchamp,remarqueschamp,consochamp,iduser) 
            VALUES(:communchamp,:latinchamp,:localchamp,:chapeauchamp,:typelameschamp,:lameschamp,:piedchamp,:chairchamp,:habitatchamp,:remarqueschamp,:consochamp,:iduser)');
        $query->bindParam(':communchamp', $nomcommun);
        $query->bindParam(':latinchamp', $nomlatin);
        $query->bindParam(':localchamp',$nomlocal);
        $query->bindParam(':chapeauchamp',$chapeau);
        $query->bindParam(':typelameschamp', $gridRadios);
        $query->bindParam(':lameschamp', $lames);
        $query->bindParam(':piedchamp',$pied);
        $query->bindParam(':chairchamp',$chair);
        $query->bindParam(':habitatchamp',$habitat);
        $query->bindParam(':remarqueschamp', $remarques);
        $query->bindParam(':consochamp', $conso);
        $query->bindParam(':iduser', $iduser);
        $query->execute();
        $query->closeCursor();
        return $this->_dataBase->lastInsertId();
    }
 
    // update une fiche si son iduser stocké dans la variable de session = iduser de la fiche 
    public function updateMushroom($iduser,$idchamp,$nomcommun,$nomlatin,$nomlocal,$conso,$chapeau,$gridRadios,$lames,$pied,$chair,$habitat,$remarques)
    {
        $query = $this->_dataBase->prepare('UPDATE champ SET communchamp=:communchamp,latinchamp=:latinchamp,localchamp=:localchamp,chapeauchamp=:chapeauchamp,typelameschamp=:typelameschamp,lameschamp=:lameschamp,piedchamp=:piedchamp,chairchamp=:chairchamp,habitatchamp=:habitatchamp,remarqueschamp=:remarqueschamp,consochamp=:consochamp WHERE idchamp=:idchamp AND champ.iduser=:iduser');
        $query->bindParam(':iduser', $iduser);
        $query->bindParam(':idchamp', $idchamp);
        $query->bindParam(':communchamp', $nomcommun);
        $query->bindParam(':latinchamp', $nomlatin);
        $query->bindParam(':localchamp',$nomlocal);
        $query->bindParam(':chapeauchamp',$chapeau);
        $query->bindParam(':typelameschamp', $gridRadios);
        $query->bindParam(':lameschamp', $lames);
        $query->bindParam(':piedchamp',$pied);
        $query->bindParam(':chairchamp',$chair);
        $query->bindParam(':habitatchamp',$habitat);
        $query->bindParam(':remarqueschamp', $remarques);
        $query->bindParam(':consochamp', $conso);
        $query->execute();
        $query->closeCursor();
    }

    public function getImagesPerMushroom($idchamp)
    {
        $query = $this->_dataBase->prepare('SELECT * FROM images WHERE idchamp = :idchamp');
        $query->bindParam(':idchamp',$idchamp);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return  $result;    
    }

    public function updatePhoto($idimg,$pathimg)
    {
        $query = $this->_dataBase->prepare('UPDATE images SET pathimg=:pathimg WHERE idimg = :idimg');
        $query->bindParam(':idimg',$idimg);
        $query->bindParam(':pathimg',$pathimg);
        $query->execute();
        $query->closeCursor();    
    }

    public function insertPhoto($data)
    {
        $query = $this->_dataBase->prepare('INSERT INTO images (pathimg,idchamp) VALUES (:pathimg,:idchamp)');
        $query->bindParam(':pathimg',  $data['pathimg']);
        $query->bindParam(':idchamp',  $data['idchamp']);
        $query->execute();
        $query->closeCursor();  
    }

    public function deleteChampMembre($idchamp,$iduser)
    { 
        $query = $this->_dataBase->prepare('DELETE FROM champ WHERE idchamp=:idchamp AND iduser=:iduser');
        $query->bindParam(':iduser', $iduser);
        $query->bindParam(':idchamp', $idchamp);
        $result = $query->execute();
        $query->closeCursor(); 
        return $result; 
    }

    public function deleteMushroomOfAdmin($idchamp)
    { 
        $query = $this->_dataBase->prepare('DELETE FROM champ WHERE idchamp=:idchamp');
        $query->bindParam(':idchamp', $idchamp);
        $result = $query->execute();
        $query->closeCursor(); 
        return $result; 
    }

    public function authorizePublication($idchamp)
    {
        $query = $this->_dataBase->prepare('UPDATE champ SET autoriserchamp = NOT autoriserchamp WHERE idchamp=:idchamp');
        $query->bindParam(':idchamp', $idchamp);
        $query->execute();
        $query->closeCursor();   
    }
}
