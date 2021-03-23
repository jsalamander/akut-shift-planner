<?php

namespace App\Form;

use App\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PlanCollectionType extends AbstractType
{
    /**
     * small hack to access the builder
     * @var
     */
    private $builder;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;
        $classes = 'form-control';
        $builder
            ->add('title', TextType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'title'
            ))
            ->add('plans', EntityType::class, array(
                'class' => 'App:Plan',
                'query_builder' => function (EntityRepository $er) {
                    $query = $er->createQueryBuilder('p')
                        ->where('p.user = :user')
                        ->andWhere('p.isTemplate = false')
                        ->andWhere('p.date >= :today')
                        ->setParameter('today', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
                        ->setParameter('user', $this->userService->getUser()->getId())
                        ->orderBy('p.date', 'ASC');

                    if ($this->builder->getData()->getId()) {
                        $query->orWhere(":collection MEMBER OF p.planCollection")
                            ->setParameter('collection',$this->builder->getData());
                    }

                    return $query;
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
            'data_class' => 'App\Entity\PlanCollection'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_plancollection';
    }


}
