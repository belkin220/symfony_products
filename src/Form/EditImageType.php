<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EditImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('image', FileType::class, [
                 'label' => 'Editar imagen',
                 // unmapped means that this field is not associated to any entity property
                 'mapped' => false,
                 // make it optional so you don't have to re-upload the image file
                 // every time you edit the Product details
                 'required' => false,
                 'data_class' => null,
                 // unmapped fields can't define their validation using annotations
                 // in the associated entity, so you can use the PHP constraint classes
                 'constraints' => [
                     new File([
                         'maxSize' => '1024k',
                         'mimeTypes' => [
                             'image/jpeg',
                             'image/png',
                             'image/webp'
                         ],
                         'mimeTypesMessage' => 'Please upload a valid image.',
                     ])
                ],
            ]);
          
    }
    public static function processImage(UploadedFile $uploaded_file, Product $product)
    {
        $path = 'uploads/images/';
        $uploaded_file_info = pathinfo($uploaded_file->getClientOriginalName());
        $file_name =
            $product->getName() . "." . $uploaded_file_info['extension'];
        $uploaded_file->move($path, $file_name);
        return $file_name;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
