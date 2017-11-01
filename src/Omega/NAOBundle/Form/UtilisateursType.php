<?php

namespace Omega\NAOBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UtilisateursType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('compte', ChoiceType::class, array(
                'choices' => array(
                    'Compte particulier' => 'particulier',
                    'Compte naturaliste' => 'naturaliste',
                    ),
                ))
            ->add('valider', SubmitType::class, array(
                'label' => 'Valider'));;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Omega\NAOBundle\Entity\Utilisateurs'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'omega_naobundle_utilisateurs';
    }


}
