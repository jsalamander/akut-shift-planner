<?php

namespace AppBundle\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use libphonenumber\PhoneNumberFormat;
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
                'label' => 'label_name',
                'required' => true
            ))
            ->add('alias', TextType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'label_alias',
                'required' => true
            ))
            ->add('email', EmailType::class, array(
                'attr'  => array('class' => $classes),
                'required' => false,
                'label' => 'email'
            ))
            ->add('phone', PhoneNumberType::class, array(
                'attr'  => array('class' => $classes),
                'required' => false,
                'label' => 'phone',
                'default_region' => 'CH',
                'format' => PhoneNumberFormat::NATIONAL
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
