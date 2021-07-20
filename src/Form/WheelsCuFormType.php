<?php

namespace App\Form;

use App\Entity\Cu;
use App\Entity\Provider;
use App\Entity\WheelsCu;
use App\Entity\CuCategories;
use App\Entity\WheelsCuType;
use Symfony\Component\Form\AbstractType;
use App\Listeners\FormValidationSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class WheelsCuFormType extends AbstractType
{
    private $formValidationSubscriber;

    public function __construct(FormValidationSubscriber $formValidationSubscriber) 
    {
        $this->formValidationSubscriber = $formValidationSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = null;
        $cu = null;

        if ($options['wheels'] && $options['wheels']->getId()) {
            $cu = $options['wheels']->getWheelsCuType() ? $options['wheels']->getWheelsCuType()->getCu() : null;
            $category = $options['wheels']->getWheelsCuType() ? $options['wheels']->getWheelsCuType()->getCuCategory() : null;
        } 
       
        $builder
            ->add('ref', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Réference'
                ]
            ])
            ->add('tavname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Désignation TAV'
                ]
            ])
            ->add('diameter', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Diamètre'
                ]
            ])
            ->add('height', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Hauteur'
                ]
            ])
            ->add('grain', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Grain'
                ]
            ])
            ->add('stock', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Stock'
                ]
            ])
            ->add('provider', EntityType::class, [
                'class' => Provider::class,
                'choice_label' => 'name',
                'label' => false
            ])
            ->add('cu', EntityType::class, [
                'class' => Cu::class,
                'choice_label' => 'name',
                'choice_value' => 'name',
                'mapped' => false,
                'label' => false,
                'data' => $cu
            ])
            ->add('categories', EntityType::class, [
                'class' => CuCategories::class,
                'choices' => $options['categories'],
                'choice_label' => 'name',
                'choice_value' => 'name',
                'mapped' => false,
                'label' => false,
                'data' => $category
            ])
            ->add('wheelsCuType', EntityType::class, [
                'class' => WheelsCuType::class,
                'choices' => $options['wheelsCuType'],
                'choice_label' => 'type',
                'choice_value' => 'type',
                'label' => false
            ]);

            //$builder->addEventSubscriber($this->formValidationSubscriber);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WheelsCu::class,
            'wheelsCuType' => null,
            'categories' => null,
            'wheels' => null
        ]);
    }
}
