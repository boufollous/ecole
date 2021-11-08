<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{

    #[Route('/student/courses', name: 'student_course_index', methods: ['GET'])]
    public function index(CourseRepository $repository): Response
    {
        $items = $repository->findAll();
        return $this->render('student/course/index.html.twig', compact('items'));
    }

    #[Route('/student/courses/{id}', name: 'student_course_show', methods: ['GET'])]
    public function show(Course $item): Response
    {
        return $this->render('student/course/show.html.twig', compact('item'));
    }
}
