<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PlanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';

        $builder
            ->add('title', null, array(
                'attr'  => array('class' => $classes)
            ))
            ->add('date', null, array(
                'attr'  => array('class' => $classes . ' datepicker'),
                'html5' => false,
                'widget' => 'single_text'
            ))
            ->add('description', null, array(
                'attr'  => array('class' => $classes)
            ))
            ->add('isTemplate', null, array(
                'attr'  => array('class' => $classes),
                'label' => 'Can this plan used as a template for future plans?'
            ))
            ->add('shifts', CollectionType::class, array(
                'entry_type' => ShiftType::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_plan';
    }


}
