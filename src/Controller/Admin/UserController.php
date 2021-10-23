<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\CrudController;
use App\Entity\User;
use App\Form\Admin\UserType;
use Symfony\Component\HttpFoundation\Response;
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
    public function new(): Response
    {
        return parent::crudNew();
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['POST', 'GET'])]
    public function edit(User $item): Response
    {
        return parent::crudEdit($item);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(User $item): Response
    {
        return parent::crudDelete($item);
    }
}
