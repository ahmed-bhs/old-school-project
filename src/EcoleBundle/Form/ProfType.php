<?php

namespace EcoleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfType extends AbstractType
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
            ->add('cin')
            ->add('dateNaissance', DateType::class)
            ->add('genre', ChoiceType::class, [
    'choices' => [
        'Male' => 'Male',
        'Female' => 'Female',
    ],
])
            ->add('email')
            ->add('competences')
            ->add('adresse')
            ->add('numeroTel')
            ->add('debut', DateType::class)
            ->add('fin', DateType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'EcoleBundle\Entity\Prof',
        ]);
    }
}
