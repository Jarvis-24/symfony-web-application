<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class SubCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryName', FormType\TextType::class,[
                'label' =>false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sub Category Name'
                ]
            ])
            ->add('parentCategory', EntityType::class, [
                'label' => 'Select Category',
                'required' => true,
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choice_label' => function (Category $category) {
                    return sprintf('%s ', $category->getCategoryName());
                },
                'query_builder' => function (CategoryRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c')
                        //->where('c.parentCategory is not null')
                        ->addOrderBy('c.categoryName');

                    return $qb;
                },
            ])
            ->add('save', FormType\SubmitType::class,[
                'label' =>'Save',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary'
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
