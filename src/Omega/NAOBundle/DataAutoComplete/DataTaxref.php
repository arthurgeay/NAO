<?php

namespace Omega\NAOBundle\DataAutoComplete;
use Doctrine\ORM\EntityManager;

class DataTaxref
{
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getData()
	{
		$repository = $this
		  ->em
		  ->getRepository('OmegaNAOBundle:Taxref')
		;

		$especes = $repository->findAll(); // On récupère les données
		$noms = array();
		foreach ($especes as $espece) {  // Boucle sur le nom de l'espèce inséré dans un tableau
		  $noms[] = $espece->getNomVern();
		}

		return $noms;
	}
}