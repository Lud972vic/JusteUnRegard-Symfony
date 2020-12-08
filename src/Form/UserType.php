<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Profil;
use App\Entity\Civilite;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Email',
                    ],
                    'label' => 'Email',
                    'required' => true,
                ])
            ->add('nom', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Nom',
                    ],
                    'label' => 'Nom',
                    'required' => true,
                ])
            ->add('prenom', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Prenom',
                    ],
                    'label' => 'Prenom',
                    'required' => true,
                ])
            ->add('pseudo', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Pseudo',
                    ],
                    'label' => 'Pseudo',
                    'required' => true,
                ])

            ->add('date_naissance', null, array(
                    'years' => range(1940, date('Y')),
                ))
                
            ->add('description', CKEditorType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'placeholder' => 'Description du média',
                        'rows' => '3',
                    ],
                    'label' => 'Description',
                    'required' => true,
                ])
            ->add('telephone', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Telephone',
                    ],
                    'label' => 'Telephone',
                    'required' => false,
                ])
            ->add('url_compte_instagram', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Instagram',
                    ],
                    'label' => 'Instagram',
                    'required' => false,
                ])
            ->add('url_compte_facebook', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'FaceBook',
                    ],
                    'label' => 'FaceBook',
                    'required' => false,
                ])
            ->add('url_compte_autre', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                        'autofocus' => 'true',
                        'placeholder' => 'Autre',
                    ],
                    'label' => 'Autre',
                    'required' => false,
                ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC')
                    ;
                },
                'choice_label' => 'nom',
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])
            ->add('civilite', EntityType::class, [
                'class' => Civilite::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.civilite', 'ASC')
                    ;
                },
                'choice_label' => 'civilite',
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])
            ->add('profil', EntityType::class, [
                'class' => Profil::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.profil', 'ASC')
                    ;
                },
                'choice_label' => 'profil',
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])
            ->add('update', SubmitType::class,
                ['attr' =>
                    ['class' => 'btn btn-outline-warning btn-sm btn-block mt-2'],
                ])
            //->add('photo')
            //->add('roles')
            //->add('password')
            //->add('isVerified')
            //->add('ip')
            //->add('created_at')
            //->add('updated_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
