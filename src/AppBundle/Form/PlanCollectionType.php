<?php

namespace AppBundle\Form;

use AppBundle\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PlanCollectionType extends AbstractType
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = 'form-control';
        $builder
            ->add('title', TextType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'title'
            ))
            ->add('plans', EntityType::class, array(
                'class' => 'AppBundle:Plan',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.user = :user')
                        ->andWhere('p.isTemplate = false')
                        ->orderBy('p.date', 'ASC')
                        ->setParameter('user', $this->userService->getUser()->getId());
                },
                'choice_label' => 'title',
                'attr'  => array('class' => $classes),
                'label' => 'plans',
                'multiple' => true
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PlanCollection'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_plancollection';
    }


}
