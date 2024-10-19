<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ExerciseType extends AbstractType
{
    // Method to build the form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',  // Label for the exercise name field
                'required' => true,  // Field is required
            ])
            ->add('muscleGroup', EntityType::class, [
                'class' => MuscleGroup::class, // Entity type linked to MuscleGroup
                'choice_label' => 'name',      // Display the name of the muscle group in the dropdown
            ])
            ->add('isBodyWeight', CheckboxType::class, [
                'label' => 'Is body weight',    // Label for the checkbox
                'required' => false,            // Field is optional
            ])
            ->add('image_file', FileType::class, [
                'label' => 'Image (JPEG/PNG)',  // Label for the image upload field
                'mapped' => false,               // Not mapped to the Exercise entity directly
                'required' => true,              // Field is required
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', // Max file size allowed
                        'mimeTypes' => [
                            'image/jpeg',      // Allowed MIME types
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG/PNG)', // Custom error message
                    ])
                ],
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Submit', // Label for the submit button
            ]);
    }

    // Method to configure options for the form
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class, // Maps the form to the Exercise entity
        ]);
    }
}
