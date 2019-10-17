<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $SubCategory = [];
        $builder
            ->add('firstName', FormType\TextType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => 'First Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', FormType\TextType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => 'Last Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', FormType\EmailType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ]
            ])
            ->add('password', FormType\PasswordType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => '*****',
                    'class' => 'form-control'
                ]
            ])
            ->add('phoneNumber', FormType\NumberType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => '9876543210',
                    'class' => 'form-control'
                ]
            ])
            ->add('description', FormType\TextareaType::class,[
                'label' =>false,
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'form-control'
                ]
            ])
            ->add('pictureFile', FormType\FileType::class,[
                'label' => 'Picture ',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('videoFile', FormType\FileType::class,[
                'label' => 'Video',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'mapped' => false,
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Select Category...',
                'choice_label' => function (Category $category) {
                    return sprintf('%s ', $category->getCategoryName());
                },
                'query_builder' => function (CategoryRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c')
                        ->where('c.parentCategory is null')
                        ->addOrderBy('c.categoryName');

                    return $qb;
                },
            ])
            /*->add('subCategory', EntityType::class, array(
                'required' => true,
                'label' => 'Sub Catgeory',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Select Sub Category...',
                'class' => Category::class,
                'choice_label' => function (Category $subcategory) {
                    return sprintf('%s ', $subcategory->getCategoryName());
                },
                'query_builder' => function (CategoryRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c')
                        ->where('c.parentCategory is not null')
                        ->addOrderBy('c.categoryName');

                    return $qb;
                },
            ))*/
            ->add('save', FormType\SubmitType::class,[
                'label' =>'Sign Up',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary'
                ]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
