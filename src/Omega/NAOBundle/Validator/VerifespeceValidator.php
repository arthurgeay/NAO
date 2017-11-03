<?php

namespace Omega\NAOBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

class VerifespeceValidator extends ConstraintValidator
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function validate($value, Constraint $constraint)
	{
		$isNotEspece = $this->em
			->getRepository('OmegaNAOBundle:Taxref')
			->isNotEspece($value)
		;

		if($isNotEspece == null)
		{
			$this->context->addViolation($constraint->message);
		}
	}
}