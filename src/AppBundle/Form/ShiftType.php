<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class ShiftType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';

        $builder
            ->add('title', TextType::class, array(
                'attr'  => array('class' => $classes),
                'required' => true,
                'label' => 'title'
            ))
            ->add('description', TextareaType::class, array(
                'attr'  => array('class' => $classes),
                'required' => true,
                'label' => 'description'
            ))
            ->add('start', TimeType::class, array(
                'attr'  => array('class' => $classes),
                'required' => true,
                'label' => 'start',
                'widget' => 'single_text'
            ))
            ->add('end', TimeType::class, array(
                'attr'  => array('class' => $classes),
                'widget' => 'single_text',
                'required' => true,
                'label' => 'end',
            ))
            ->add('numberPeople', IntegerType::class, array(
                'attr'  => array('class' => $classes),
                'required' => true,
                'label' => 'number_people'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Shift'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_shift';
    }


}
