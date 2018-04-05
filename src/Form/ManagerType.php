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
        $data = $options['data'] ?? null;
        $can_edit = $options['can_edit'];

        $builder
            ->add('first_name', Type\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter First Name',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('last_name', Type\TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Last Name',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('login', Type\EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Login',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('role', Type\ChoiceType::class, [
                'choices' => [
                    'manager' => "Manager",
                ],
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Active' => 1,
                    'Disabled' => 0
                ],
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit
                ]
            ])
            ->add('read_order', Type\CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit,
                    'checked' => $data ? $data->getReadOrder() : false
                ]
            ])
            ->add('edit_order', Type\CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit,
                    'checked' => $data ? $data->getEditOrder() : false
                ]
            ])
            ->add('read_manager', Type\CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit,
                    'checked' => $data ? $data->getReadManager() : false
                ]
            ])
            ->add('edit_manager', Type\CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit,
                    'checked' => $data ? $data->getEditManager() : false
                ]
            ])
            ->add('read_configuration', Type\CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit,
                    'checked' => $data ? $data->getReadConfiguration() : false
                ]
            ])
            ->add('edit_configuration', Type\CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => !$can_edit,
                    'checked' => $data ? $data->getEditConfiguration() : false,
                ]
            ])
            ->add('submit', Type\SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'disabled' => !$can_edit
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Manager::class,
            'can_edit' => false
        ]);
    }
}
