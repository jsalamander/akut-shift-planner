<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PlanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';

        $builder
            ->add('title', TextType::class, array(
                'attr'  => array('class' => $classes)
            ))
            ->add('date', DateType::class, array(
                'attr'  => array('class' => $classes . ' datepicker'),
                'html5' => false,
                'widget' => 'single_text'
            ))
            ->add('description', TextareaType::class, array(
                'attr'  => array('class' => $classes),
                'required' => false
            ))
            ->add('isTemplate', CheckboxType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'Can this plan used as a template for future plans?',
                'required' => false
            ))
            ->add('shifts', CollectionType::class, array(
                'entry_type' => ShiftType::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => false,
                'required' => true
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
