<?php

namespace App\Form;

use App\Entity\ExerciseLog;  // Make sure to import ExerciseLog
use App\Entity\Workout;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkoutType extends AbstractType
{
    // Method to build the form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',  // Label for the workout name field
            ])
            ->add('exerciseLogs', CollectionType::class, [
                'entry_type' => ExerciseLogType::class,  // Specify the type of entries in the collection
                'entry_options' => ['label' => false],   // No label for each entry
                'allow_add' => true,                      // Allow adding multiple exercise logs
                'by_reference' => false,                  // Ensure the collection is updated properly
                'allow_delete' => true,                   // Allow removal of exercise logs
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Add',  // Label for the submit button
            ]);
    }

    // Method to configure options for the form
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workout::class, // Maps the form to the Workout entity
        ]);
    }
}
