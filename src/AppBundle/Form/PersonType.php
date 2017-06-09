<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class PersonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';

        $builder
            ->add('name', TextType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'Name (visible for creator only)',
                'required' => true
            ))
            ->add('alias', TextType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'Alias (public)',
                'required' => true
            ))
            ->add('email', EmailType::class, array(
                'attr'  => array('class' => $classes),
                'required' => false
            ))
            ->add('phone', TextType::class, array(
                'attr'  => array('class' => $classes),
                'required' => false
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_person';
    }


}
