<?php
// fonction twig qui converti le lien en dur en adresse réécrit
$twig = new \Twig\Environment($loader);

// Renvoi l'image correspondante 
$getImage = new \Twig\TwigFunction('getImage', function ($comestible) {
    $conso = [
        '/assets/icones/mortel.svg' => 'Mortel',
        '/assets/icones/toxique.svg' => 'Toxique',
        '/assets/icones/a-rejeter.svg' => 'A rejeter',
        '/assets/icones/comestible-mediocre.svg' => 'Comestible médiocre',
        '/assets/icones/bon-comestible.svg' => 'Bon comestible',
        '/assets/icones/excellent-comestible.svg' => 'Excellent comestible'
    ];
    return array_search($comestible,$conso);
});
$twig->addFunction($getImage);

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
