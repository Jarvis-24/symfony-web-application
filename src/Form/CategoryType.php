<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryName', FormType\TextType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => 'Category Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('save', FormType\SubmitType::class,[
                'label' =>'Save',
                'attr' => [
                    'class' => 'btn-primary form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
