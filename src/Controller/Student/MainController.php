<?php

declare(strict_types=1);

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/student", name="student_index", methods={"GET"})
     * @return Response
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function index(): Response
    {
        return $this->render('student/index.html.twig');
    }

}
