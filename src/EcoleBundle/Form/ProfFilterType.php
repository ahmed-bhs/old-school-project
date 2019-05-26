<?php

namespace EcoleBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('nom', Filters\TextFilterType::class)
            ->add('prenom', Filters\TextFilterType::class)
            ->add('cin', Filters\NumberFilterType::class)
            ->add('dateNaissance', Filters\DateTimeFilterType::class)
            ->add('genre', Filters\TextFilterType::class)
            ->add('email', Filters\TextFilterType::class)
            ->add('competences', Filters\TextFilterType::class)
            ->add('adresse', Filters\TextFilterType::class)
            ->add('numeroTel', Filters\NumberFilterType::class)
            ->add('debut', DateType::class)
            ->add('fin', DateType::class)

            ->add('seances', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Seance',
                    'choice_label' => 'description',
            ])
            ->add('evalutions', Filters\EntityFilterType::class, [
                    'class' => 'EcoleBundle\Entity\Evaluation',
                    'choice_label' => 'description',
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
