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
            ->add('Nom', TextType::class)
            ->add('Email', TextType::class)
            ->add('Sujet', TextType::class)
            ->add('Votre message', TextType::class)
            ->add('Valider', SubmitType::class, array(
                'label' => 'Valider'));
    }

    public function getName ()
    {
        return 'omega_naobundle_contact';
    }

}