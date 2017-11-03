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
    	if($file != null)
    	{
    		$filename = md5(uniqid()).'.'.$file->guessExtension();
			$file->move($this->targetDir, $filename);
	    	$observation->setPhoto($filename);
    	}

	}

}