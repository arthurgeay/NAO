<?php

namespace Omega\NAOBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RechercheType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('espece', TextType::class)
            ->add('rechercher', SubmitType::class, array(
                'label' => 'rechercher'));
    }

    public function getName ()
    {
        return 'omega_naobundle_recherche';
    }

}