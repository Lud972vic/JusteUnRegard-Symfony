<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Media;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Libellé du média',
                        //'disabled' => 'disabled'
                    ],
                    'label' => 'Libelle',
                    'required' => true,
                ])
            //TextType
            ->add('description', CKEditorType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'placeholder' => 'Description du média',
                        'rows' => '3',
                    ],
                    'label' => 'Description',
                    'required' => true,
                ])
            // ->add('categorie', EntityType::class, [
            //     'class' => Categorie::class,
            //     'choice_label' => function(Categorie $categorie) {
            //         return sprintf('(%s - %s) - %s', 'Sous-catégorie', $categorie->getType() , $categorie->getNom());
            //     },
            //     'placeholder' => 'Choose a category',
            //     'required' => true,
            // ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.type = :val')
                        ->setParameter('val', 3)
                        ->orderBy('u.nom', 'ASC')
                    ;
                },
                'choice_label' => 'nom',
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])

            ->add('imageFile', VichImageType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Libellé du média',
                    ],
                    'label' => 'Media',
                    'required' => false,
                    'download_link' => false,
                    'delete_label' => ' ',
                ])
            ->add('publish', SubmitType::class,
                ['attr' =>
                    ['class' => 'btn btn-outline-warning btn-sm btn-block mt-2'],
                ])
            ->add('nom', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2 disabled',
                        'disabled' => 'disabled'],
                ])
            ->add('created_at')
            ->add('updated_at')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
