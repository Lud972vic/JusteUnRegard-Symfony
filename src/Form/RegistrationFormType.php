<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Profil;
use App\Entity\Civilite;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', EntityType::class, [
                'class' => Civilite::class,
                'required' => true,
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])
            ->add('nom', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('prenom', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('pseudo', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('email', EmailType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2',
                    ],
                ])
            ->add('profil', EntityType::class, [
                    'class' => Profil::class,
                    'required' => true,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            // ->add('date_naissance', DateType::class,
            //     ['attr' =>
            //         ['class' => 'form-control mb-2'],
            //     ])
            ->add('date_naissance', null, array(
                'years' => range(1940, date('Y')),
            ))
            ->add('photo', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('description', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('telephone', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('url_compte_instagram', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('url_compte_facebook', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('url_compte_autre', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('ip', TextType::class,
                ['required' => false,
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'attr' =>
                ['class' => 'form-control mb-2'],
            ])
            // ->add('ville', EntityType::class, [
            //     'class' => Ville::class,
            //     'required' => true,
            //     'attr' =>
            //     ['class' => 'form-control mb-2'],
            // ])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
