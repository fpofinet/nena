<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ItemOrdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('posologie',ChoiceType::class, [
                'choices'  => [
                    "3 Prises par jour(matin-midi-soir)" => "3P",
                    "2 Prises par jour( matin-soir)" => "2P",
                    "1 Prise par jour" => "1P",
                    "1 comprimé avant chaque repas" => "1R",
                ],
                'label'=>'Posologie',
            ])
            ->add('quantite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
