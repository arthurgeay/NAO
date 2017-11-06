<?php

namespace Omega\NAOBundle\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Omega\NAOBundle\Entity\Observations;

class UploadedPhotos
{
	private $targetDir;

	public function __construct($targetDir)
	{
		$this->targetDir = $targetDir;
	}

	public function upload(Observations $observation)
	{
		$file = $observation->getPhoto();
		// Si la photo est importée
    	if($file != null)
    	{
    		$filename = md5(uniqid()).'.'.$file->guessExtension(); // On génère un nom unique
			$file->move($this->targetDir, $filename); // On le déplace dans le répertoire
	    	$observation->setPhoto($filename); // On modifie son nom avec le nom généré
    	}

	}

	public function remove(Observations $observation)
	{
		$file = $observation->getPhoto();

		if($file != null)
		{
			$path = 'uploads/img/'.$file;
			unlink($path);
		}
	}

}