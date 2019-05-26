<?php

namespace EcoleBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('debut', TimeType::class)
            ->add('fin', TimeType::class)
            ->add('jour', ChoiceType::class, [
    'choices' => [
        'Lundi' => 2,
        'Mardi' => 3,    'Mercredi' => 4,    'Jeudi' => 5,    'Vendredi' => 6,    'Samedi' => 7,
    ],
])
            ->add('classe', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Classe',
                'choice_label' => 'description',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('prof', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Prof',
                'choice_label' => 'nom',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'EcoleBundle\Entity\Seance',
        ]);
    }
}
