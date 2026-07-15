<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', null, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    '5 stars' => 5,
                    '4 stars' => 4,
                    '3 stars' => 3,
                    '2 stars' => 2,
                    '1 star' => 1,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Rating',
                'attr' => ['class' => 'star-rating'],
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('reviewText', TextareaType::class, [
                'attr' => ['rows' => 4],
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('authorEmail', EmailType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
