<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Expense;
use App\Entity\Fourcount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('amount')
            ->add('description')
            ->add('date', DateType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
            
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'datepicker form-control'],
            ])
            ->add('paid_by', EntityType::class, [
                'class' => User::class,
                'choice_label' => "name",
                'multiple' => false,
                'choices' => $options['fourcount']->getParticipants(),
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => "name",
                'multiple' => true,
                'choices' => $options['fourcount']->getParticipants(),
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
            'fourcount' => false,
        ]);
    }
}
