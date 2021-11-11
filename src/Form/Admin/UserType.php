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
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    public function __construct(
        private Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // on vérifie si on crée ou édit un utilisateur
        $data = $builder->getData();
        $isEdit = $data instanceof User && $data->getId() !== null;

        // on vérifie le role de l'utilisateur connecté pour ajouter certains champ en fonction
        $user = $this->security->getUser();
        $isAdmin = $user && $this->security->isGranted('ROLE_ADMIN', $user);

        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class);

        // seul l'administrateur peut confirmer l'inscription d'un utilisateur
        if ($isAdmin) {
            $builder->add('is_confirmed', CheckboxType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
