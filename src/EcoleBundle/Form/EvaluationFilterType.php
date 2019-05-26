<?php

namespace EcoleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class EvaluationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('description', Filters\TextFilterType::class)
            ->add('coef', Filters\NumberFilterType::class)
            ->add('date', Filters\DateTimeFilterType::class)
        
            ->add('classe', Filters\EntityFilterType::class, array(
                    'class' => 'EcoleBundle\Entity\Classe',
                    'choice_label' => 'description',
            )) 
            ->add('prof', Filters\EntityFilterType::class, array(
                    'class' => 'EcoleBundle\Entity\Prof',
                    'choice_label' => 'nom',
            )) 
            ->add('notes', Filters\EntityFilterType::class, array(
                    'class' => 'EcoleBundle\Entity\Note',
                    'choice_label' => 'id',
            )) 
        ;
        $builder->setMethod("GET");


    }

    public function getBlockPrefix()
    {
        return null;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
