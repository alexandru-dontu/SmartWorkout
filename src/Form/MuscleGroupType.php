<?php

namespace App\Form;

use App\Entity\MuscleGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MuscleGroupType extends AbstractType
{
    // Method to build the form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',   // Label for the muscle group name field
                'required' => true,  // Field is required
            ])
            ->add('bodyPart', TextType::class, [
                'label' => 'Body Part', // Label for the body part field
                'required' => true,     // Field is required
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Submit',    // Label for the submit button
            ]);
    }

    // Method to configure options for the form
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MuscleGroup::class, // Maps the form to the MuscleGroup entity
        ]);
    }
}
