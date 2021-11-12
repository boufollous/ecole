<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\CrudController;
use App\Entity\Slot;
use App\Form\Admin\SlotType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/slot', name: 'admin_slot_')]
class SlotController extends CrudController
{
    protected string $route = 'admin_course';
    protected string $entity = Slot::class;
    protected string $form = SlotType::class;
    protected string $template_path = 'admin/course';

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
    public function edit(Slot $item): Response
    {
        return parent::crudEdit($item);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(Slot $item): Response
    {
        return parent::crudDelete($item);
    }
}
