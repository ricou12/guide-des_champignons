<?php
/*
Redimensionne les photos.
Crée les images et les stockent dans les dossiers
 précédement crées dans la class upload_file.
*/

class ResizeImage
{
	public function resize($filename,$maxSize) 
	{
		// Définition de la largeur et de la hauteur maximale
		$width = $maxSize;
		$height = $maxSize;

		// Content type
		header('Content-Type: image/jpeg');

		// Cacul des nouvelles dimensions
		list($width_orig, $height_orig) = getimagesize($filename);

		$ratio_orig = $width_orig/$height_orig;

		if ($width/$height > $ratio_orig) 
		{
			$width = $height*$ratio_orig;
		} 
		else 
		{
			$height = $width/$ratio_orig;
		}

		// Redimensionnement
		$image_p = imagecreatetruecolor($width, $height);

		// $path = pathinfo($filename,PATHINFO_DIRNAME);

		// $name = pathinfo($filename,  PATHINFO_FILENAME);

		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		switch ( strtolower($extension) )
		{
			case 'jpg':
				$image = imagecreatefromjpeg($filename);
				break;
			case 'jpeg':
				$image = imagecreatefromjpeg($filename);
				break;
			case 'gif':
				$image = imagecreatefromgif($filename);
				break;
			case 'png':
				$image = imagecreatefrompng($filename);
				break;
		}

		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

		// Affichage
		// imagejpeg($image_p, null, 100);

		// Remplacement de l'image dans le dossier d'origine.
		imagejpeg($image_p,$filename);

		// Libération de la mémoire
		imagedestroy($image_p);
	}
}


