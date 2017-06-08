<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';

        $builder
            ->add('name', null, array(
                'attr'  => array('class' => $classes),
                'label' => 'Name (visible for creator only)'
            ))
            ->add('alias', null, array(
                'attr'  => array('class' => $classes),
                'label' => 'Alias (public)'
            ))
            ->add('email', null, array(
                'attr'  => array('class' => $classes)
            ))
            ->add('phone', null, array(
                'attr'  => array('class' => $classes)
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
