<?php

namespace App\Form;

use App\Form\Data\Manager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class ManagerType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        /** @var Manager $data */
        $data = $options['data'];
        $disabled = $data ? !$data->getEditManager() : false;

        $builder
            ->add('first_name', Type\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter First Name',
                    'disabled' => $disabled
                ]
            ])
            ->add('last_name', Type\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Last Name',
                    'disabled' => $disabled
                ]
            ])
            ->add('login', Type\EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Login',
                    'disabled' => $disabled
                ]
            ])
            ->add('role', Type\ChoiceType::class, [
                'choices' => [
                    'manager' => "Manager",
                ],
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled
                ]
            ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Active' => 1,
                    'Disabled' => 0
                ],
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled
                ]
            ])
            ->add('read_order', Type\CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled,
                    'checked' => $data ? $data->getReadOrder() : false
                ]
            ])
            ->add('edit_order', Type\CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled,
                    'checked' => $data ? $data->getEditOrder() : false
                ]
            ])
            ->add('read_manager', Type\CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled,
                    'checked' => $data ? $data->getReadManager() : false
                ]
            ])
            ->add('edit_manager', Type\CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled,
                    'checked' => $data ? $data->getEditManager() : false
                ]
            ])
            ->add('read_configuration', Type\CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled,
                    'checked' => $data ? $data->getReadConfiguration() : false
                ]
            ])
            ->add('edit_configuration', Type\CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled,
                    'checked' => $data ? $data->getEditConfiguration() : false
                ]
            ])
            ->add('submit', Type\SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $disabled
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Manager::class,
        ]);
    }
}
