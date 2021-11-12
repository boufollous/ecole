<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\Repository\SlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SlotController extends AbstractController
{
    #[Route('/student/slots', name: 'student_slot_index', methods: ['GET'])]
    public function index(SlotRepository $repository): Response
    {
        $items = $repository->findAll();
        return $this->render('student/slot.html.twig', compact('items'));
    }
}
