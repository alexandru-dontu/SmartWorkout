<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    // Method to build the form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',   // Label for the user's name field
                'required' => true,  // Field is required
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',  // Label for the email field
                'required' => true,  // Field is required
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password', // Label for the password field
                'required' => true,    // Field is required
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Register',  // Label for the submit button
            ]);
    }

    // Method to configure options for the form
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Maps the form to the User entity
        ]);
    }
}
