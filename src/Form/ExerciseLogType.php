<?php

namespace App\Form;

use App\Entity\Exercise;
use App\Entity\ExerciseLog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseLogType extends AbstractType
{
    // Method to build the form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duration', IntegerType::class, [
                'label' => 'Duration', // Label for the duration field
                'required' => true,     // Field is required
            ])
            ->add('reps', IntegerType::class, [
                'label' => 'Reps',      // Label for the reps field
                'required' => true,     // Field is required
            ])
            ->add('sets', IntegerType::class, [
                'label' => 'Sets',      // Label for the sets field
                'required' => true,     // Field is required
            ])
            ->add('exercise', EntityType::class, [
                'class' => Exercise::class, // Entity type linked to Exercise
                'choice_label' => 'name',   // Display the name of the exercise in the dropdown
            ])
            ->add('weight', IntegerType::class, [
                'label' => 'Weight',     // Label for the weight field
                'required' => false,     // Field is optional
            ])
        ;
    }

    // Method to configure options for the form
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExerciseLog::class, // Maps the form to the ExerciseLog entity
        ]);
    }
}
