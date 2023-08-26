<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('contact')
            ->add('password',PasswordType::class)
            ->add('roles',ChoiceType::class,[
                'choices'=>[
                    'Medecin'=>"ROLE_MEDECIN",
                    'Receptioniste'=>"ROLE_RECEPTIONISTE",
                    'Admin'=>"ROLE_ADMIN"
                ],
            ])
            ->add('Valider',SubmitType::class) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
