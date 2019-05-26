<?php

namespace EcoleBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('valeur', Filters\NumberFilterType::class)

            ->add('classe', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Classe',
                    'choice_label' => 'description',
            ])
            ->add('evaluation', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Evaluation',
                    'choice_label' => 'description',
            ])
            ->add('etudiant', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Etudiant',
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
