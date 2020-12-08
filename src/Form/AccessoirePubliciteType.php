<?php

namespace App\Form;

use App\Entity\AccessoirePublicite;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AccessoirePubliciteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'autofocus' => 'true',
                    'placeholder' =>
                    'Libellé de l\'accessoire ou de la publicité',
                ],
                'label' => 'Libelle',
                'required' => true,
            ])
            ->add('description', CKEditorType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' =>
                    'Description de l\'accessoire ou de la publicité',
                    'rows' => '3',
                ],
                'label' => 'Description',
                'required' => true,
            ])
            ->add('disponibilite', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'],
                'choices' => [
                    'En stock' => true,
                    'Vendu' => false,
                ],
                'label' => 'Disponibilité',
                'required' => true,
            ])
            ->add('imageFile', VichImageType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'autofocus' => 'true',
                    'placeholder' => 'Libellé du média',
                ],
                'label' => 'Accessoire\Publicité',
                'required' => false,
                'download_link' => false,
                'delete_label' => ' ',
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2 disabled',
                    'disabled' => 'disabled',
                ],
            ])
            ->add('prix', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'autofocus' => 'true',
                    'placeholder' =>
                    'Prix de l\'accessoire ou de la publicité',
                ],
                'label' => 'Prix',
                'required' => true,
            ])
            ->add('typeannonce')
            ->add('publish', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-warning btn-sm btn-block mt-2',
                ],
            ])
            ->add('created_at')
            ->add('updated_at')
            ->add('user');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccessoirePublicite::class,
        ]);
    }
}
