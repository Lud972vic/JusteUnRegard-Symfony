<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Votre e-mail',
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('message', CKEditorType::class, [
                'required' => true,
                'label' => 'Votre message',
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => 'mt-2 btn btn-outline-warning form-control mb-2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
