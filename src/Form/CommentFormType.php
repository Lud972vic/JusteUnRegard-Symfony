<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CommentFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*Je vérifie qu'un utilisateur est connecté, afin d'afficher le formulaire de connexion*/
        $user = $this->security->getUser();

        if ($user) {
            $builder
                ->add('contenu', TextareaType::class,
                    ['attr' =>
                        ['class' => 'form-control mb-2'],
                    ]
                )
                ->add('rgpd', CheckboxType::class, [
                    'label' => 'I agree to the collection of my data...',
                    'attr' =>
                    ['class' => 'form-control mb-2'],
                ])
                ->add('Send', SubmitType::class,
                    ['attr' =>
                        ['class' => 'btn btn-outline-dark btn-sm btn-block mt-2'],
                    ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
