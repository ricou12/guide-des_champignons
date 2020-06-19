<?php
// URL REWRITING : fonction twig qui réecrit les urls en adresse propre.
// Change l'image pour le bouton page admin et membre lorsque l'on inverse l'etat
$function = new \Twig\TwigFunction('url_rewrite', function ($url) {
    // extrait les parametres de l'url
    $_SERVER['QUERY_STRING'];

    
});
$twig->addFunction($function);


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

// Active le lien des composants de navigation pour la page en cours.
$function = new \Twig\TwigFunction('is_active', function ($nameOfTemplate,$link) {
    if($link === $nameOfTemplate){
        return 'active';
    }
});
$twig->addFunction($function);