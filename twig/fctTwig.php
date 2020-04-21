<?php
// fonction twig qui converti le lien en dur en adresse réécrit
// $twig = new \Twig\Environment($loader);

// Renvoi l'image correspondante 
$function = new \Twig\TwigFunction('getImage', function ($comestible) {
    $conso = [
        '/assets/icones/mortel.png' => 'Mortel',
        '/assets/icones/toxique.png' => 'Toxique',
        '/assets/icones/a-rejeter.png' => 'A rejeter',
        '/assets/icones/comestible-mediocre.png' => 'Comestible médiocre',
        '/assets/icones/bon-comestible.png' => 'Bon comestible',
        '/assets/icones/excellent-comestible.png' => 'Excellent comestible',
        '/assets/icones/sans.png' => ''
    ];
    return array_search($comestible,$conso);
});
$twig->addFunction($function);

// Change l'image pour le bouton page admin et membre lorsque l'on inverse l'etat
$function = new \Twig\TwigFunction('toogleImg', function ($img) {
    if($img)
    {
       return "/assets/icones/checked.png";
    }
    else
    {
        return "/assets/icones/check.png";
    }
});
$twig->addFunction($function);

// Récupère la page active
$function = new \Twig\TwigFunction('is_active', function ($titre,$link) {
    $PageTitleAdminOrMembre = ['Administrateur','Membre'];
    if($link === 'comptes'){
        if(in_array($titre, $PageTitleAdminOrMembre)) return 'active';
    }

    if($link === $titre){
        return 'active';
    }
});
$twig->addFunction($function);
