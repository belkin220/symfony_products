<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('image', FileType::class, [
            'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image.(jpg, jpg png or webp extensions) '
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'attr' =>['class' => 'form-control'],
            ])
            ->add('description')
            ->add('price', NumberType::class, [
                'grouping' => true,
                'input' => 'number',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
