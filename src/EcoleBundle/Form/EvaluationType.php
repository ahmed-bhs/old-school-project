<?php

namespace EcoleBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('coef')
            ->add('date')
            ->add('classe', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Classe',
                'choice_label' => 'description',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
            ])->add('semestre', ChoiceType::class, [
    'choices' => [
        '1' => '1',
        '2' => '2',
    ],
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
            'data_class' => 'EcoleBundle\Entity\Evaluation',
        ]);
    }
}
