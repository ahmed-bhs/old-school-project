<?php

namespace EcoleBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('status', CheckboxType::class, [])
            ->add('dateNaissance', DateType::class)
            ->add('adresse')
            ->add('nomPere')
            ->add('nomMere')
            ->add('numeroTel')
            ->add('genre', ChoiceType::class, [
    'choices' => [
        'Male' => 'Male',
        'Female' => 'Female',
    ],
])
            ->add('classe', EntityType::class, [
                'class' => 'EcoleBundle\Entity\Classe',
                'choice_label' => 'description',
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
            'data_class' => 'EcoleBundle\Entity\Etudiant',
        ]);
    }
}
