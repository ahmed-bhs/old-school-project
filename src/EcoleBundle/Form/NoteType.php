<?php

namespace EcoleBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valeur')
            ->add('classe', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Classe',
                'choice_label' => 'description',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('evaluation', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Evaluation',
                'choice_label' => 'description',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('etudiant', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Etudiant',
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
            'data_class' => 'EcoleBundle\Entity\Note',
        ]);
    }
}
