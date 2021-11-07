<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\CrudController;
use App\Entity\Course;
use App\Form\Admin\CourseType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/course', name: 'admin_course_')]
class CourseController extends CrudController
{
    protected string $route = 'admin_course';
    protected string $entity = Course::class;
    protected string $form = CourseType::class;
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
    public function edit(Course $item): Response
    {
        return parent::crudEdit($item);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(Course $item): Response
    {
        return parent::crudDelete($item);
    }
}
