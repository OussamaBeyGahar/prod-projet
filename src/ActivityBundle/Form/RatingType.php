<?php

namespace ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('security', ChoiceType::class, [
            'choices'  => [
                '*' => 1,
                '**' => 2,
                '***' => 3,
            ],
        ])
            ->add('beauty', ChoiceType::class, [
                'choices'  => [
                    '*' => 1,
                    '**' => 2,
                    '***' => 3,
                ],
            ] )
            ->add('budget', ChoiceType::class, [
                'choices'  => [
                    '*' => 1,
                    '**' => 2,
                    '***' => 3,
                ],
            ])
            ->add('Ajouter', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ActivityBundle\Entity\Rating'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'activitybundle_rating';
    }


}
