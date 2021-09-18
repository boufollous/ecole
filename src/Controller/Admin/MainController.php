<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller\Admin
 * @author bernard-ng <bernard@devscast.tech>
 */
class MainController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_index", methods={"GET"})
     * @return Response
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
