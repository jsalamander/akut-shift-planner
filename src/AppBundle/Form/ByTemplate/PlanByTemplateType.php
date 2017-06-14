<?php

namespace AppBundle\Form\ByTemplate;

use AppBundle\Service\PlanService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class PlanByTemplateType extends AbstractType
{
    /**
     * @var PlanService
     */
    private $planService;

    /**
     * PlanByTemplateType constructor.
     * @param PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';

        $builder
            ->add('templates', ChoiceType::class, array(
                'choices' => $this->planService->getPlans(),
                'choice_label' => function($plan, $key, $index) {
                    return $plan->getTitle();
                },
                'attr'  => array('class' => $classes),
                'label' => 'template_to_be_used',
                'mapped' => false
            ))
            ->add('title', null, array(
                    'attr'  => array('class' => $classes),
                    'label' => 'new_title'
                )
            )->add('date', DateType::class, array(
                'attr'  => array('class' => $classes . ' datepicker'),
                'html5' => false,
                'widget' => 'single_text',
                'label' => 'date'
            ))
            ->add('description', TextareaType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'description',
                'required' => true
            ))
            ->getForm();
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plan',
            'validation_groups' => array('new_from_template')
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
