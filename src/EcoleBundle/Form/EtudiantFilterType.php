<?php

namespace EcoleBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('nom', Filters\TextFilterType::class)
            ->add('prenom', Filters\TextFilterType::class)
            ->add('status', Filters\BooleanFilterType::class)
            ->add('dateNaissance', DateType::class)
            ->add('adresse', Filters\TextFilterType::class)
            ->add('nomPere', Filters\TextFilterType::class)
            ->add('nomMere', Filters\TextFilterType::class)
            ->add('numeroTel', Filters\NumberFilterType::class)
            ->add(
                'genre',
                Filters\ChoiceFilterType::class,
                [
                    'choices' => [
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ],
                ]
            )
            ->add(
                'classe',
                Filters\EntityFilterType::class,
                [
                    'class' => 'EcoleBundle\Entity\Classe',
                    'choice_label' => 'description',
                ]
            );
        $builder->setMethod('GET');
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'allow_extra_fields' => true,
                'csrf_protection' => false,
                'validation_groups' => ['filtering'], // avoid NotBlank() constraint-related message
            ]
        );
    }
}
