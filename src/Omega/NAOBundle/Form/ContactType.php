<?php

namespace Omega\NAOBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class)
            ->add('Email', TextType::class)
            ->add('Sujet', TextType::class)
            ->add('Message', TextareaType::class)
            ->add('Valider', SubmitType::class, array(
                'label' => 'Envoyer'));
    }

    public function getName ()
    {
        return 'omega_naobundle_contact';
    }

}