<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\CrudController;
use App\Entity\User;
use App\Form\Admin\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends CrudController
{
    protected string $route = 'admin_user';
    protected string $entity = User::class;
    protected string $form = UserType::class;
    protected string $template_path = 'admin/user';

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return parent::crudIndex();
    }

    #[Route('/new', name: 'new', methods: ['POST', 'GET'])]
    public function new(UserPasswordHasherInterface $hasher): Response
    {
        // on utilise un callback pour modifier l'entité en gardant le comportant du controller parent
        // on définit le role d'un etudiant sur la plateforme
        return parent::crudNew(
            callback: function (User $item) use ($hasher): User {
                return $this->hashUserPassword($item, $hasher)
                    ->setRoles(roles: ["ROLE_USER"]);
            });
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['POST', 'GET'])]
    public function edit(User $item, UserPasswordHasherInterface $hasher): Response
    {
        // on hash le mot de passe meme apres edition pour permettre la connexion
        return parent::crudEdit(item: $item, callback: function (User $item) use ($hasher): User {
            return $this->hashUserPassword(item: $item, hasher: $hasher);
        });
    }

    #[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(User $item): Response
    {
        return parent::crudDelete($item);
    }

    /**
     * On hash le mot de passe de l'utilisateur pour permettre la connexion
     * @param User $item
     * @param UserPasswordHasherInterface $hasher
     * @return User
     */
    private function hashUserPassword(User $item, UserPasswordHasherInterface $hasher): User
    {
        // on rehash le mot de passe uniquement s'il a été modifié
        if ($item->getPassword() !== null) {
            $item->setPassword(
                password: $hasher->hashPassword(
                    user: $item,
                    plainPassword: $item->getPassword()
                )
            );
        }
        return $item;
    }
}
