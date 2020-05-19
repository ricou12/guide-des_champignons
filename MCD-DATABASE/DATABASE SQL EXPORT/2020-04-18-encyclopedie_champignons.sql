-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 18 avr. 2020 à 15:36
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `encyclopedie_champignons`
--

-- --------------------------------------------------------

--
-- Structure de la table `champ`
--

DROP TABLE IF EXISTS `champ`;
CREATE TABLE IF NOT EXISTS `champ` (
  `idchamp` int(11) NOT NULL AUTO_INCREMENT,
  `autoriserchamp` tinyint(4) NOT NULL DEFAULT '0',
  `datechamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `latinchamp` varchar(255) DEFAULT NULL,
  `communchamp` varchar(255) NOT NULL,
  `localchamp` varchar(255) DEFAULT NULL,
  `chapeauchamp` text,
  `typelameschamp` enum('','Adnées','Décurrentes','Echancrées','Emarginées','Libres','Sécédentes','Sinuées','Subdécurrentes') DEFAULT NULL,
  `lameschamp` text,
  `piedchamp` text,
  `chairchamp` text,
  `habitatchamp` text,
  `remarqueschamp` text,
  `consochamp` enum('','Excellent comestible','Bon comestible','Comestible médiocre','A rejeter','Toxique','Mortel') DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  PRIMARY KEY (`idchamp`),
  KEY `champ_utilisateurs_FK` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `champ`
--

INSERT INTO `champ` (`idchamp`, `autoriserchamp`, `datechamp`, `latinchamp`, `communchamp`, `localchamp`, `chapeauchamp`, `typelameschamp`, `lameschamp`, `piedchamp`, `chairchamp`, `habitatchamp`, `remarqueschamp`, `consochamp`, `iduser`) VALUES
(1, 1, '2020-03-20 23:00:00', 'BOLETUS EDULIS', 'Cèpe de Bordeaux', NULL, 'de 10 à 20 cm, hémisphérique puis convexe, charnu, de couleur allant du beige au marron à brun noisette ou brun ochracé clair, parfois plus clair selon exposition, à marge épaisse, souvent excédente et généralement soulignée d\'un fin liseré blanchâtre.', '', 'tubes fins et serrés prolongés de pores d\'abord de couleur blanche à blanchâtre puis jaune, devenant ensuite verdâtre sur la fin.', 'robuste et ferme, renflé à ventru ou allongé en massue vers la base, de couleur fauve strié d\'un fin réseau blanc en saillie, devenant blanc vers la base.', 'blanche.', NULL, 'excellent comestible.', 'Excellent comestible', 1),
(2, 1, '2020-03-20 23:00:00', 'BOLETUS AEREUS', 'Cèpe bronzé', '', 'de 10 à 20 cm hémisphérique puis convexe, charnu, à surface plus ou moins bosselée et cuticule veloutée devenant mate, à marge lisse et régulière devenant très faiblement sinueuse, de couleur brun foncé à reflets bronzés, brun ochracés ou chamois, parfois plus clair selon l\'exposition.', '', ' tubes fins de couleur blanchâtre devenant crème puis jaune à jaune verdâtre en vieillissant', '6 à 10 cm, très trapu, robuste, renflé à la base, obèse même, de couleur chamois à roux, strié d\'un fin réseau d\'abord blanc puis brun ; en bordure de sentier, lorsqu\'il est bien exposé, il est parfois très court , le camouflant ainsi davantage dans la végétation ...', 'saveur et odeur douces.', 'thermophile, il pousse de la fin de l\'été au début de l\'hiver, généralement dans les chênaies aérées.', 'La chair du bolet bronzé est blanche et ferme lorsqu\'il est frais et les spores sont de couleur brun olivâtre. Rarement isolé, il est le plus souvent entouré de plusieurs individus de la même espèce. Ses couleurs peuvent être claires ou sombres et il est assez fréquent de voir des individus à la cuticule aux couleurs zonées. Il s\'agit d\'un des plus fins champignons des bois qui dégage son arôme avec intensité à la cuisson.', 'Excellent comestible', 1),
(3, 1, '2020-03-20 23:00:00', 'AMANITA MUSCARIA', 'Amanite tue mouches', NULL, 'après avoir émergé du sol, le chapeau est couvert de nombreuses verrues blanches en forme de pyramides.', '', 'libres, sont blanches, de même que l\'empreinte de spore.', 'est blanc, il mesure 5 à 20 centimètres de haut pour 1 à 2 centimètres de diamètre, et a la texture fibreuse et légèrement friable typique de la plupart des grands champignons. À sa base, la volve (ou bulbe) porte des résidus du voile universel sous la forme d\'un ou deux anneaux concentriques.', 'blanche.', 'Amanita muscaria est un champignon cosmopolite, qui croît dans les forêts de conifères et de feuillus de toutes les régions tempérées et boréales de l\'hémisphère Nord.', 'toxique, hallucinogène.', 'Toxique', 1),
(4, 1, '2020-03-20 23:00:00', 'AMANITA CAESAREA', 'Amanite des césars', 'Oronge', 'de 8 à 15-20 cm, est ovoïde jeune puis hémisphérique et enfin convexe. La cuticule est rouge orangé vif, luisante, assez souvent couvert de grands lambeaux de la volve, blancs.', '', 'serrées, jaune d\'or clair.', 'de 8 à 15 cm est robuste, droit ou légèrement courbé, de la couleur des lames. La base du stipe est ovoïde vers le sol. La volve est épaisse, blanc grisâtre, s\'écartant du stipe et ample.Son anneau membraneux, concolore au pied, placé haut et pendant. L\'odeur est agréable, sa saveur douce de noix. La sporée est blanche. ', 'ferme, blanche, jaune sous la cuticule. La marge est nettement striée, jaune d\'or.', NULL, 'sa réputation d\'excellent comestible, sa beauté et sa relative rareté en font un champignon recherché.', 'Excellent comestible', 1),
(5, 1, '2020-03-20 23:00:00', 'SUILLUS COLLINITUS', 'Bolet enduit', 'Pissacan', 'de 4 à 10/15 cm., globuleux à hémisphérique puis convexe à plan-convexe ;\r\ncuticule visqueuse, collante, brillante, facile à séparer, beige jaunâtre à orangé jaunâtre, orangé ocre ou encore brun ;\r\nmarge concolore, incurvée, un peu excédante .', '', 'adnés à décurrents, séparables, jaune pâle puis se salissant dans l\'âge - pores fins, réguliers, anguleux, jaune crème pâle puis jaunâtres à jaune vif, exsudant des gouttes laiteuses chez les jeunes spécimens.', '4-10 cm de hauteur, 1 à 2 cm de diam., élancé, égal ou partie inférieure renflée et parfois coudée, base atténuée, plein et mou, blanchâtre à jaunâtre, brunâtre en partie inférieure, ponctué de granulations blanchâtres laiteuses puis jaunissantes et brunissantes.', ' de blanchâtre à jaune pâle, plus soutenu dans le pied, non grisonnante ni bleuissante, ferme jeune vite amollie, goût douceâtre, légèrement acidulée, odeur agréable, épicée', 'sous les pins et plus particulièrement pins d’Alep et les milieux herbeux. C\'est une espèce très commune que l\'on rencontre du mois de juillet au mois d\'octobre, toujours sous les pins ou peu éloigné d\'eux, mais toujours en étroite relation avec les racines (mycorhizes), et généralement en terrain calcaire. ', 'considéré comme comestible. il reste cependant laxatif.', 'A rejeter', 1),
(6, 1, '2020-03-20 23:00:00', 'SUILLUS LUTEUS', 'Bolet jaune', 'Nonnette voilée', 'de 5 à 12 cm, hémisphérique puis convexe, à marge lisse, de couleur brune à brun ochracé, parfois nettement plus clair et jusqu\'à jaune, à l\'aspect brillant et visqueux.', '', 'relativement longs, adnés à légèrement décurrents, séparables, jaunes ;\r\npores très petits ronds puis anguleux dans l\'âge, concolores aux tubes puis brun-olivâtre, immuables à la pression.', '4-10 x 1,5-3 cm, plein, ferme, cylindrique, court et massif, blanchâtre à jaunâtre, plutôt granuleux vers le sommet .\r\nAnneau membraneux, ample, blanchâtre teinté de violet', 'épaisse, tendre, aqueuse, cotonneuse dans l\'âge, blanchâtre, jaune vers les tubes, immuable à la coupe, bonne odeur fruitée, saveur légèrement acidulée', 'Sous les pins', 'bon comestible à l\'état juvénile.\r\nIl porte un anneau membraneux formé d\'un voile tendu entre le haut du pied et la marge du chapeau', 'Bon comestible', 1),
(7, 1, '2020-03-20 23:00:00', 'SUILLUS VARIEGATUS', 'Bolet moucheté', '', 'brun, Crème, Gris, Jaune. Entièrement feutré mécheux ou moucheté.', '', 'brun, Jaune, Vert.', 'brun, Crème, Gris, Jaune.', 'blanc, Crème, Jaune.', 'Sous les pins sur sol calcaire', 'comestible.', 'Comestible médiocre', 1),
(8, 1, '2020-03-20 23:00:00', 'LACTARIUS SANGUIFLUUS', 'Lactaire sanguin', 'Sanguin, rouge', 'blanc cassé à orangé,verdissant.', '', 'rosé à reflets vineux, tachées de vert et de rouge vineux dans les blessures.', 'rosé vineux pâle,blanchâtre au sommet,vite creux.', 'blanchatre a la coupe puis sécretion d\'un latex rouge vineux', 'sous les pins maritimes, d’Alep..., en mélange avec chênes, arbousiers, bruyères,romarin.', 'excellent comestible.', 'Excellent comestible', 1),
(9, 1, '2020-03-20 23:00:00', 'LACTARIUS DELICIOSUS', 'Lactaire délicieux', 'Safrané', 'convexe puis creusé, orangé,marqué de petite tache concentrique,tache blanche, verdissant dans les blessures.', '', 'orangé se tachant orange vif puis verdissant aux endroits blessés.', 'blanchâtre à orangé pâle, creux.', 'blanchâtre se colorant d\'un lait couleur carotte.', 'en automne sous pins maritimes,sylvestre, en forêts mixtes pins, chênes, bruyère.', 'Bon comestible', 'Bon comestible', 1),
(10, 1, '2020-03-20 23:00:00', 'LECCINUM LEPIDUM', 'Bolet joli', 'Bolet du chène vert', NULL, '', NULL, NULL, NULL, NULL, NULL, 'Comestible médiocre', 1),
(11, 1, '2020-03-20 23:00:00', 'LACTARIUS TESQUORUM', 'Lactaire des lieux sauvages', '', 'Crème, Jaune, Saumon', '', 'Crème, Jaune, Saumon.', 'Blanc, Crème, Saumon.', 'latex blanc saveur âcre.', 'sous les pins.', 'Sans intérêt.', 'A rejeter', 1),
(12, 1, '2020-03-20 23:00:00', 'RUSSULA VIRESCENS', 'Russule verdoyante', 'Palomet', 'chapeau vert allant du vert pâle au vert-bleu-gris mesurant de 5 à 10 cm, souvent craquelé et parsemé de petites plaques lui donnant un aspect de moisi.', '', 'blanc-crème, assez serrées.', 'est blanc, cassant (comme chez toutes les russules) et mesure 1,5 à 2 cm de diamètre pour 4 à 8 cm de hauteur.', 'blanche avec une odeur fongique faible et une saveur de noisette', 'Sous feuillus(chêne, hêtre, châtaignier),sous conifères.', 'excellent comestible, sans doute la meilleure des russules.', 'Excellent comestible', 1),
(13, 1, '2020-03-20 23:00:00', 'MACROLEPIOTA PROCERA', 'Lepiote élevée', 'Coulemelle', 'ovoïde puis convexe et enfin largement étalé en parasol, de 10 à 30 cm de diamètre, surmonté d\'un mamelon, avec une cuticule sèche, gris-roux, couvert d\'écailles brunes, plus denses vers le centre.', '', 'blanches, serrées, molles', 'haut de 15 à 40 cm, est élancé, creux, bulbeux à la base, brun tigré et de plus en plus écaillé en allant vers la base. Il est pourvu d\'un anneau double, blanchâtre et coulissant. Le pied est fibreux, ce qui le rend immangeable. ', 'est blanche, molle, avec une odeur et une saveur fruitées agréables.', 'sur sol à tendance acide de type schiteux. sous-bois dégagés ou les clairières. ', 'Comestible recherché.', 'Bon comestible', 1),
(14, 1, '2020-03-20 23:00:00', 'BOLETUS AESTIVALIS', 'Cèpe d\'été', 'Cèpe réticulé', 'jusqu’à 25 cm, hémisphérique puis convexe, sec, velouté, brun noisette.', '', 'blancs puis jaunâtres ou ocre.', 'de 5 à 15 cm, ventru, allongé, réseau saillant, brun plus pâle que le chapeau.', 'blanche, épaisse, ferme.', 'bois de feuillus, lisières, souvent sous hêtres & chênes, clairières.', 'de Mai à Septembre\r\nLe cèpes d’été est un des meilleurs cèpes, incontestablement ! C’est le plus précoce, mais on le trouve rarement après le mois de septembre.', 'Excellent comestible', 1),
(15, 1, '2020-03-20 23:00:00', 'CANTHARELLUS CIBARIUS', 'Chanterelle comestible', 'Girolle', 'de 2 à 12 cm, convexe puis centre légèrement déprimé avec l\'âge, d\'une belle couleur jaune d\'œuf .\r\nmarge d\'abord enroulée, puis sinueuse, lobée et fragile .\r\ncuticule séparable, sèche, unie.', '', 'assez espacés, parfois ressemblant à de grossières lamelles, fourchus, largement décurrents sur le stipe', 'de 4 à 7 cm et ~1,5 de diam, charnu, plein, inégal et s\'amincissant vers la base, sec, concolore au chapeau', ' 	\r\n\r\ncompacte, blanc-crème, légèrement fibreuse dans le pied, jamais attaquée par les larves', 'Les girolles se ramassent de juin à novembre, selon les conditions météorologiques, dans les bois sous les feuillus ou les conifères, et poussent en groupes, c\'est à dire qu\'il est bien rare de ne trouver qu\'une chanterelle : elles sont souvent nombreuses sur une petite zone, dès lors que le sol est chaud et humide. Les régions de prédilection des girolles sont la Sologne, le sud-ouest, le massif central, les Vosges. ', 'Sa croissance est assez lente : 1 à 2 semaines pour arriver à maturité, mais les girolles ont une longévité pouvant atteindre 1 mois. \r\nConfusion possible avec le clitocybe de l\'olivier (Omphalotus olearius). Toutefois, les deux sont quand même très différents : le clitocybe de l\'olivier est souvent beaucoup plus gros, il possède de vraies lames sous le chapeau, un pied strié et il pousse essentiellement sur des vieilles souches de bois, en touffes. ', 'Bon comestible', 1),
(16, 1, '2020-03-20 23:00:00', 'CANTHARELLUS LUTESCENS', 'Chanterelle jaunissante', '', 'de 2 à 5 cm jaune brun ; Marge ondulée.', '', 'veiné, jaune pâle, sporée crème.', 'arrondi à aplati, creux, jaune, creux, jaune orangé.', 'fibreuse. odeur fruitée saveur douce.', 'en colonie sous feuillus, conifère souvent sous bruyère. Sur sol a tendance acide de type schisteux dans le sud de la France.', 'bon comestible.', 'Bon comestible', 1),
(17, 1, '2020-03-20 23:00:00', 'RAMARIA FLAVA', 'Clavaire jaune', 'Barbe', 'non différencié, forme coralloïde, Haut. 5 à 12 cm, Diam. 6 à 15 cm.', '', 'lisse.', 'tronc épais.', 'blanchâtre. Odeur légère, agréable.', 'sous les chênes.', ' comestible médiocre.', 'Comestible médiocre', 1),
(18, 1, '2020-03-20 23:00:00', 'COPRINUS COMATUS', 'Coprin chevelu', 'Goutte d\'encre', 'de 5 à 12 cm de hauteur, est blanc, cylindrique et couvert de peluches blanchâtres. Son sommet est plus foncé et nu. En vieillissant, il s\'évase pour former des cloches ; le bord noircit et exsude des gouttelettes noires. Au-dessous, les lames d\'abord blanches noircissent.', '', 'blanches virent rapidement au rose puis au noir.  L\'anneau est léger, mobile, et ne présente pas de volve.', 'long (jusqu\'à plus de 20 cm) est creux et cylindrique, un peu renflé en bulbe, blanc, avec un petit anneau en forme de bague qui finit par se détacher.', '', 'Le coprin chevelu apparaît dans la pelouse, dans les prés, au bord de chemins et des routes. Il apprécie les sols riches en matière organique, notamment ceux qui ont bénéficié d\'apports de fumier. Il décompose et recycle cette matière organique dans le sol. Ce champignon est un excellent indicateur d\'une terre riche en matière organique et en azote et donc idéal pour des graminées à gazon. Il pousse dans toutes les régions. ', 'considéré comme un excellent comestible, à condition toutefois de le consommer jeune, c\'est-à-dire entièrement blanc à la coupe, s\'il y a du noir il est à rejeter.', 'Bon comestible', 1),
(19, 1, '2020-03-20 23:00:00', 'SARCOSPHAERA CORONARIA', 'Pezize étoilée', 'Golbelet', NULL, '', 'en forme de pomme de terre, blanchâtre à l\'état jeune puis s\'ouvrant en étoile. Face intérieur( hyménium) blanchâtre puis vite violet et enfin brun violet. face exterieur blanchetachée de jaune.', NULL, 'blanchâtre à violacée, cassante. Odeur et saveurs faible.', 'Sous conifères, chênes sur sol calcaire.', 'toxique(mortel cru).', 'Toxique', 1),
(20, 1, '2020-03-20 23:00:00', 'Hydnum repandum', 'Hydne commun', 'Pied-de-mouton. Barbe de chèvre. Hydne sinué. Hydne commun. Langue-de-veau.', 'de 4 à 15 cm, massif, de forme très variable, irrégulièrement bosselé, souvent soudé les uns aux autres, convexe puis étalé, déprimé ;\r\ncuticule adnée, sèche, mate, de crème à chamois, non zonée et non écailleuse ;\r\nmarge épaisse, largement enroulée au début puis s\'étalant et devenant lobée et sinueuse.', '', 'serrés, décurrents, longs de 3-5 mm, fragiles, blancs puis crèmes et brunissants.', 'épais, charnu, trapu, plein, de 3 à 8 x 1 à 4 cm, difforme, excentré rarement central, s\'épaississant à la base, blanchâtre puis roussissant par endroits. ', 'épaisse, ferme, cassante, blanche fonçant légèrement au contact de l\'air, très légère odeur agréable, saveur parfois légèrement amère.', NULL, 'c\'est un excellent comestible, mais non de premier choix, sa chair présentant souvent l\'inconvénient d\'une légère amertume.', 'Excellent comestible', 1),
(21, 1, '2020-03-20 23:00:00', 'Gomphus clavatus', 'Chanterelle violette', NULL, '5 à 10 cm de diamètre, un peu déprimé au sommet; marge sinueuse, devenant lobée; lilas, puis brun-jaune.', '', 'sinueux, fourchus et boursoufflés, violacés, puis brun-ocre.', 'court, souvent latéral, plus mince en bas, violet, devenant plus terne avec l\'âge.', 'blanche et ferme.', 'conifères ( pins, sapins ) surtout en montagne.Souvent en touffes.Fin de l\' été et automne.Peu courant à assez courant selon les régions.', 'bon comestible jeune, légèrement amer par la suite.Ce champignon est apprécié pour sa chair plus tendre que la girolle mais il est aussi moins parfumé que cette dernière.', 'Bon comestible', 1),
(22, 1, '2020-03-20 23:00:00', 'Geastrum fimbriatum', 'Géastre sessile', NULL, 'Sphérique de 2 à 4 cm de diamètre avant ouverture.\r\nIl éclate en 6 à 10 segments ocre avec une boule au centre allant de l\' ocre au gris parfois brun surmontée d\' une pointe plus sombre.', '', NULL, NULL, 'molle blanche à grise.', NULL, 'non comestible', 'A rejeter', 1),
(23, 1, '2020-03-20 23:00:00', '', 'Satyre puant', 'œuf du Diable', 'dans son premier stade, le phallus se présente sous forme d\'un œuf oblong.À la coupe, l\'œuf montre plusieurs couches en allant vers le centre : d\'abord une enveloppe externe, le péridium, puis une épaisse couche translucide et gélatineuse, puis une couche plus foncée et plus dense, la future gléba, et enfin au centre, un corps blanc, le futur stipe.\r\n\r\nChapeau de3 à 4 cm, conique, tronqué au sommet par un petit cercle, alvéolé et blanc mais entièrement recouvert au début d\'une gléba vert-olive à noirâtre. Celle-ci attire les mouches qui l\'emportent, disséminent ainsi les spores, et mettent à nu les alvéoles blanches.', '', NULL, '10 à 20 cm sur 2 à 4 cm de diamètre, allongé et s\'amincissant graduellement vers le haut, blanc, alvéolé, creux, caverneux et fragile - il s\'affaisse fréquemment au bout de quelques jours. Il est chaussé d\'une volve, reste de l\'œuf éclaté.', 'blanche et poreuse, inodore en elle-même. En revanche la gléba, tant qu\'elle est présente, répand une odeur pestilentielle de cadavre que l\'on sent à plusieurs mètres à la ronde avant même d\'avoir aperçu le champignon.', NULL, 'L\'œuf, débarrassé de sa gélatine, est comestible cru, par exemple en salade, et un goût se situant entre le radis et le raifort. Il est surtout consommé en Extrême-Orient. Une fois le champignon développé, son odeur de charogne proscrit toute utilisation culinaire. ', 'Comestible médiocre', 1),
(24, 1, '2020-03-20 23:00:00', 'clitocybe odora', 'Clitocybe anisé', NULL, '6 (10) cm de diam, convexe puis plat, légèrement omboné-déprimé ;\r\nrevêtement vert finement fibrilleux, vert, bleu-vert surtout au disque, pâlissant et décoloré dans l\'âge, sec, non visqueux ;\r\nmarge ', '', 'adnées, très légèrement décurrentes, blanchâtres à concolores au chapeau mais plus pâles, inégales, moyennement serrées, parfois fourchues, veinées sur la face et interveinées dans le fond, surtout sur vieux sujets', '8 x 1,5 cm, cylindrique, blanchâtre plus ou moins bleuté verdâtre, pruineux, floconneux-fibrilleux, base velue légèrement épaissie.', 'blanc sale à bleutée, élastique, peu épaisse sauf au disque, odeur forte d\'anis, saveur douce fortement anisée', NULL, 'bon comestible, mais très (trop) parfumé, à utiliser de préférence en condiment', 'Bon comestible', 1),
(25, 1, '2020-03-20 23:00:00', 'Morchella elata', 'Morille conique', NULL, '6 à 12 cm, conique, brun à brun-noir, côtes longitudinales rectilignes.', '', ' alvéoles irrégulières', 'jusqu’à 12 cm, trapu, crème, creux, granuleux', 'Crème, peu épaisse', NULL, NULL, 'Excellent comestible', 1),
(26, 1, '2020-03-20 23:00:00', 'Fomes fomentarius', 'Amadouvier', '', 'Le chapeau, de 10 à 40, voire 50 cm de large, et de 10 à 20 cm d\'épaisseur, en sabot de cheval, gris pâle, blanchissant, avec une marge crème à brun noisette est en réalité une concrétion de multiples chapeaux empilés les uns sous les autres. Ils sont ainsi gravés de sillons concentriques délimitant des bourrelets de taille croissante en allant vers la base, indiquant chaque nouvelle fructification.', '', 'Les tubes sont longs, bruns et totalement stratifiés car, ici encore, à chaque poussée de ce champignon pérennant , une nouvelle couche de tubes recouvre la précédente. Les pores sont gris clair à noisette, petits, arrondis, environ 0,2 mm. La sporée est jaunâtre.', 'il n\'y a pas de pied, le champignon pousse en console.', ' La chair est subéreuse, très difficile à couper, de couleur fauve à brun foncé. La croûte est épaisse, très coriace, gris noirâtre et brillante à la coupe. Les exemplaires frais dégagent une odeur de pomme. ', 'C\'est un redoutable parasite de plusieurs feuillus : hêtre, platane, bouleau, peuplier, chêne ou marronnier, entre autres. Il se fixe sur des arbres faibles ou blessés, produit une pourriture blanche et finit en quelques années par tuer son hôte. ', 'l\'amadouvier n\'est pas comestible. Il était utilisé comme substance inflammable dès la Préhistoire.', '', 1),
(27, 1, '2020-03-20 23:00:00', 'Rhizopogon', 'Rhizopogon rose', NULL, 'en forme de pomme de terre, blanchâtre à jaune.', '', 'gléba spongieux,jaunâtre puis olive foncé à maturation. Sporée olivâtre.', NULL, NULL, 'à demi enterrré sous les pins en automne.', 'A rejeter. Il existe des nombreuse espèces de rhizopogon, très difficile à différencier les unes des autres.', 'A rejeter', 1),
(28, 1, '2020-03-20 23:00:00', 'Lycoperdon perlatum', 'Vesse-de-loup perlée', NULL, 'de 3 à 5 cm pour 4 à 9 cm de hauteur, en forme de globe, d\'ampoule globuleuse ou de poire inversée, à mamelon obtus plus ou moins marqué, blanc à blanc crème brunissant, couverte de petites aspérités coniques dont une partie disparaît en vieillissant.', '', 'néant', 'non marqué, dans le prolongement du carpophore, également recouvert des mêmes aspérités que celui-ci', 'blanc bistre.', 'principalement dans les bois de conifères, en lisières, talus et chemins les bordant, parfois aussi dans les herbus contigus moins fréquemment sous les feuillus', ' considérées comme comestibles quand elles sont jeunes et que la gleba est homogène et blanche.', 'Comestible médiocre', 1);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `idimg` int(11) NOT NULL AUTO_INCREMENT,
  `pathimg` varchar(255) NOT NULL,
  `idchamp` int(11) NOT NULL,
  PRIMARY KEY (`idimg`),
  KEY `images_champ_FK` (`idchamp`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`idimg`, `pathimg`, `idchamp`) VALUES
(1, 'AMADOUVIER-01.jpg', 26),
(2, 'AMADOUVIER-02.jpg', 26),
(3, 'AMANITE-DES-CESARS-01.jpg', 4),
(4, 'AMANITE-TUE-MOUCHES-01.jpg', 3),
(5, 'AMANITE-TUE-MOUCHES-02.jpg', 3),
(6, 'AMANITE-TUE-MOUCHES-03.jpg', 3),
(7, 'AMANITE-TUE-MOUCHES-04.jpg', 3),
(8, 'AMANITE-TUE-MOUCHES-05.jpg', 3),
(9, 'AMANITE-TUE-MOUCHES-06.jpg', 3),
(10, 'AMANITE-TUE-MOUCHES-07.jpg', 3),
(11, 'BOLET-ENDUIT-01.jpg', 5),
(12, 'BOLET-ENDUIT-02.jpg', 5),
(13, 'BOLET-ENDUIT-03.jpg', 5),
(14, 'BOLET-JAUNE-01.jpg', 6),
(15, 'BOLET-JAUNE-02.jpg', 6),
(16, 'BOLET-JAUNE-03.jpg', 6),
(17, 'BOLET-JAUNE-04.jpg', 6),
(18, 'BOLET-JOLI-01.jpg', 10),
(19, 'BOLET-JOLI-02.jpg', 10),
(20, 'BOLET-JOLI-03.jpg', 10),
(21, 'BOLET-JOLI-04.jpg', 10),
(22, 'BOLET-MOUCHETE-01.jpg', 7),
(23, 'BOLET-MOUCHETE-02.jpg', 7),
(24, 'CEPE-BRONZE-01.jpg', 2),
(25, 'CEPE-BRONZE-02.jpg', 2),
(26, 'CEPE-BRONZE-03.jpg', 2),
(27, 'CEPE-DE-BORDEAUX-01.jpg', 1),
(28, 'CEPE-DE-BORDEAUX-02.jpg', 1),
(29, 'CEPE-DE-BORDEAUX-03.jpg', 1),
(30, 'CEPE-DE-BORDEAUX-04.jpg', 1),
(31, 'CEPE-DE-BORDEAUX-05.jpg', 1),
(32, 'CEPE-DE-BORDEAUX-06.jpg', 1),
(33, 'CEPE-DE-BORDEAUX-07.jpg', 1),
(34, 'CEPE-ETE-01.jpg', 14),
(35, 'CEPE-ETE-02.jpg', 14),
(36, 'CEPE-ETE-03.jpg', 14),
(37, 'CHANTERELLE-COMESTIBLE-01.jpg', 15),
(38, 'CHANTERELLE-COMESTIBLE-02.jpg', 15),
(39, 'CHANTERELLE-COMESTIBLE-03.jpg', 15),
(40, 'CHANTERELLE-COMESTIBLE-04.jpg', 15),
(41, 'CHANTERELLE-COMESTIBLE-05.jpg', 15),
(42, 'CHANTERELLE-COMESTIBLE-06.jpg', 15),
(43, 'CHANTERELLE-COMESTIBLE-07.jpg', 15),
(44, 'CHANTERELLE-COMESTIBLE-08.jpg', 15),
(45, 'CHANTERELLE-COMESTIBLE-09.jpg', 15),
(46, 'CHANTERELLE-COMESTIBLE-10.jpg', 15),
(47, 'CHANTERELLE-COMESTIBLE-11.jpg', 15),
(48, 'CHANTERELLE-COMESTIBLE-12.jpg', 15),
(49, 'CHANTERELLE-COMESTIBLE-13.jpg', 15),
(50, 'CHANTERELLE-COMESTIBLE-14.jpg', 15),
(51, 'CHANTERELLE-JAUNISSANTE-01.jpg', 16),
(52, 'CHANTERELLE-JAUNISSANTE-02.jpg', 16),
(53, 'CHANTERELLE-JAUNISSANTE-03.jpg', 16),
(54, 'CHANTERELLE-JAUNISSANTE-04.jpg', 16),
(55, 'CHANTERELLE-JAUNISSANTE-05.jpg', 16),
(56, 'CHANTERELLE-JAUNISSANTE-06.jpg', 16),
(57, 'CHANTERELLE-VIOLETTE-01.jpg', 21),
(58, 'CHANTERELLE-VIOLETTE-02.jpg', 21),
(59, 'CHANTERELLE-VIOLETTE-03.jpg', 21),
(60, 'CLAVAIRE-JAUNE-01.jpg', 17),
(61, 'CLAVAIRE-JAUNE-02.jpg', 17),
(62, 'CLAVAIRE-JAUNE-03.jpg', 17),
(63, 'CLAVAIRE-JAUNE-04.jpg', 17),
(64, 'CLAVAIRE-JAUNE-05.jpg', 17),
(65, 'CLITOCYBE-ANISE-01.jpg', 24),
(66, 'CLITOCYBE-ANISE-02.jpg', 24),
(67, 'COPRIN-CHEVELU-01.jpg', 18),
(68, 'COPRIN-CHEVELU-02.jpg', 18),
(69, 'COPRIN-CHEVELU-03.jpg', 18),
(70, 'COPRIN-CHEVELU-04.jpg', 18),
(71, 'COPRIN-CHEVELU-05.jpg', 18),
(72, 'COPRIN-CHEVELU-06.jpg', 18),
(73, 'COPRIN-CHEVELU-07.jpg', 18),
(74, 'GEASTRE-SESSILE-01.jpg', 22),
(75, 'GEASTRE-SESSILE-02.jpg', 22),
(76, 'GEASTRE-SESSILE-03.jpg', 22),
(77, 'HYDNE-COMMUN-01.jpg', 20),
(78, 'HYDNE-COMMUN-02.jpg', 20),
(79, 'HYDNE-COMMUN-03.jpg', 20),
(80, 'HYDNE-COMMUN-04.jpg', 20),
(81, 'HYDNE-COMMUN-05.jpg', 20),
(82, 'HYDNE-COMMUN-06.jpg', 20),
(83, 'LACTAIRE-DELICIEUX-01.jpg', 9),
(84, 'LACTAIRE-DELICIEUX-02.jpg', 9),
(85, 'LACTAIRE-DELICIEUX-03.jpg', 9),
(86, 'LACTAIRE-DELICIEUX-04.jpg', 9),
(87, 'LACTAIRE-LIEUX-SAUVAGES-01.jpg', 11),
(88, 'LACTAIRE-LIEUX-SAUVAGES-02.jpg', 11),
(89, 'LACTAIRE-LIEUX-SAUVAGES-03.jpg', 11),
(90, 'LACTAIRE-SANGUIN-01.jpg', 8),
(91, 'LACTAIRE-SANGUIN-02.jpg', 8),
(92, 'LACTAIRE-SANGUIN-03.jpg', 8),
(93, 'LACTAIRE-SANGUIN-04.jpg', 8),
(94, 'LACTAIRE-SANGUIN-05.jpg', 8),
(95, 'LACTAIRE-SANGUIN-06.jpg', 8),
(96, 'LACTAIRE-SANGUIN-07.jpg', 8),
(97, 'LACTAIRE-SANGUIN-08.jpg', 8),
(98, 'LACTAIRE-SANGUIN-09.jpg', 8),
(99, 'LACTAIRE-SANGUIN-10.jpg', 8),
(100, 'LACTAIRE-SANGUIN-11.jpg', 8),
(101, 'LEPIOTE-ELEVEE-01.jpg', 13),
(102, 'LEPIOTE-ELEVEE-02.jpg', 13),
(103, 'LEPIOTE-ELEVEE-03.jpg', 13),
(104, 'LEPIOTE-ELEVEE-04.jpg', 13),
(105, 'LEPIOTE-ELEVEE-05.jpg', 13),
(106, 'MORILLE-CONIQUE-01.jpg', 25),
(107, 'MORILLE-CONIQUE-02.jpg', 25),
(108, 'MORILLE-CONIQUE-03.jpg', 25),
(109, 'MORILLE-CONIQUE-04.jpg', 25),
(110, 'MORILLE-CONIQUE-05.jpg', 25),
(111, 'MORILLE-CONIQUE-06.jpg', 25),
(112, 'PEZIZE-ETOILEE-01.jpg', 19),
(113, 'RHIZOPOGON-ROSE-01.jpg', 27),
(114, 'RUSSULE-VERDOYANTE-01.jpg', 12),
(115, 'RUSSULE-VERDOYANTE-02.jpg', 12),
(116, 'RUSSULE-VERDOYANTE-03.jpg', 12),
(117, 'RUSSULE-VERDOYANTE-04.jpg', 12),
(118, 'RUSSULE-VERDOYANTE-05.jpg', 12),
(119, 'RUSSULE-VERDOYANTE-06.jpg', 12),
(122, 'VESSE-DE-LOUP-PERLEE-01.jpg', 28),
(123, 'VESSE-DE-LOUP-PERLEE-02.jpg', 28),
(124, 'VESSE-DE-LOUP-PERLEE-03.jpg', 28),
(125, 'VESSE-DE-LOUP-PERLEE-04.jpg', 28);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `dateuser` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nomuser` varchar(100) DEFAULT NULL,
  `prenomuser` varchar(100) DEFAULT NULL,
  `roleuser` enum('Administrateur','Modérateur','Membre') NOT NULL DEFAULT 'Membre',
  `passworduser` varchar(255) NOT NULL,
  `pseudouser` varchar(255) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`iduser`, `dateuser`, `nomuser`, `prenomuser`, `roleuser`, `passworduser`, `pseudouser`) VALUES
(0, '2020-03-09 23:00:00', NULL, NULL, 'Administrateur', '$2y$10$1UyUiHerZecq84Amj2OwS.RItD9QeWgs2ISjXnCqgQpjSot9DSaju', 'admin'),
(1, '2020-04-02 06:51:30', NULL, NULL, 'Membre', '$2y$10$QMaLbh24IU3htMMdsy1r1eKsBjQr/lA9Gq5v3jc7dxjLz4UrZDEne', 'Eric'),
(6, '2020-04-18 04:12:58', NULL, NULL, 'Membre', '$2y$10$r2vcFx5L6nyOU4VSdsPgouwYk7WOI74tU74cbTl779kzQTbCpKWXO', 'vahiné'),
(8, '2020-04-18 08:33:39', NULL, NULL, 'Membre', '$2y$10$06gG.bTrtuxSbFAiCiDikeLgACY4GiZ7FoQwv.4u3d/bM1E70Goa.', 'Nana');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `champ`
--
ALTER TABLE `champ`
  ADD CONSTRAINT `champ_utilisateurs_FK` FOREIGN KEY (`iduser`) REFERENCES `utilisateurs` (`iduser`) ON DELETE SET NULL;
--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`idchamp`) REFERENCES `champ` (`idchamp`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
