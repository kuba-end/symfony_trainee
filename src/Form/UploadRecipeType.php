<?php

namespace App\Form;

use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadRecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipe', CKEditorType::class, [
                'label' => ' ',
                'required' => true,
                'help' => 'Share recipe for your dish with others',
                'attr' => ['class' => 'mt-4 mb-4'],
                'config' => ['toolbar' => 'standard'],
            ] );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}