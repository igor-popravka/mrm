<?php

namespace App\Form;

use App\Form\Data\Product;
use App\Service\ISO4217;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class ProductType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {
        /** @var Product $data */
        $can_edit = $options['can_edit'];

        $builder
            ->add('code', Type\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('name', Type\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('cost', Type\NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('currency', Type\ChoiceType::class, [
                'choices' => ISO4217::getCryptoList(),
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('assets', Type\HiddenType::class)
            ->add('submit', Type\SubmitType::class, [
                'label' => 'Continue',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'disabled' => !$can_edit
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'can_edit' => false
        ]);
    }
}
