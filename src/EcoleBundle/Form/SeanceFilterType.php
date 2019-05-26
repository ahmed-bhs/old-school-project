<?php

namespace EcoleBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('description', Filters\TextFilterType::class)
            ->add('debut', TimeType::class)
            ->add('fin', TimeType::class)
            ->add('jour', ChoiceType::class, [
    'choices' => [
        'Lundi' => 2,
        'Mardi' => 3,    'Mercredi' => 4,    'Jeudi' => 5,    'Vendredi' => 6,    'Samedi' => 7,
    ],
])

            ->add('classe', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Classe',
                    'choice_label' => 'description',
            ])
            ->add('prof', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Prof',
                    'choice_label' => 'nom',
            ])
        ;
        $builder->setMethod('GET');
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => ['filtering'], // avoid NotBlank() constraint-related message
        ]);
    }
}
