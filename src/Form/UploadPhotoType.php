<?php

namespace App\Form;

use App\Entity\Photo;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename',FileType::class, [
                'label'=> ' ',
                'help' => 'Upload photo of your meal',
                'attr' => ['class' => 'mb-4 mt-4'],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'File must be an image',
                    ])
                ]
                ]

            )
            ->add('is_public', CheckboxType::class, [
                'label' => 'Public',
                'required' => false

            ])
            ->add('recipe', CKEditorType::class, [
                'label' => ' ',
                'required' => true,
                'help' => 'Share recipe for your dish with others',
                'attr' => ['class' => 'mt-4 mb-4'],
                'config' => ['toolbar' => 'standard'],
                'mapped' => true
            ] )
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
