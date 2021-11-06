<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // on vérifie si on crée ou édit un utilisateur
        $data = $builder->getData();
        $edit = $data instanceof User && $data->getId() !== null;

        $builder
            ->add('email', EmailType::class);

            // le mot de pass est obligation seulement à la création
            if (!$edit) {
                $builder->add('password', PasswordType::class);
            }

            $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('is_confirmed', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
