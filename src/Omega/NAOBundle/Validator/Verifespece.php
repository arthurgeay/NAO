<?php

namespace Omega\NAOBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Verifespece extends Constraint
{
	public $message = "Cette espèce n'existe pas dans notre base de donnée";

	public function validateBy()
	{
		return 'omega_nao_espece';
	}
}