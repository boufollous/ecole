<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class CrudController extends AbstractController
{
    protected string $route;
    protected string $entity;
    protected string $form;
    protected string $template_path;

    public function __construct(
        protected EntityManagerInterface $em,
        protected RequestStack           $stack
    )
    {
    }

    public function crudIndex(): Response
    {
        $items = $this->em->getRepository($this->entity)->findAll();
        return $this->render(
            view: "{$this->template_path}/index.html.twig",
            parameters: compact('items')
        );
    }

    public function crudNew(): Response
    {
        $item = new $this->entity;
        $form = $this->createForm($this->form, $item);
        $form->handleRequest($this->stack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($item);
            $this->em->flush();

            return $this->redirectToRoute(
                route: "{$this->route}_index",
                status: Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            view: "{$this->template_path}/new.html.twig",
            parameters: [
                'form' => $form->createView(),
                'item' => $item
            ]
        );
    }

    public function crudEdit(object $item): Response
    {
        $form = $this->createForm($this->form, $item);
        $form->handleRequest($this->stack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute(
                route: "{$this->route}_index",
                status: Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            view: "{$this->template_path}/edit.html.twig",
            parameters: [
                'form' => $form->createView(),
                'item' => $item
            ]
        );
    }

    public function crudDelete(object $item): Response
    {
        $request = $this->stack->getCurrentRequest();
        $isAjaxRequest = $request->get('ajax', false);
        $token = $isAjaxRequest ?
            json_decode($request->getContent())->{'_token'} :
            $request->get('_token');
        $isTokenValid = $this->isCsrfTokenValid("delete_{$item->getId()}", $token);

        if ($isTokenValid) {
            $this->em->remove($item);
            $this->em->flush();
        }

        if ($isAjaxRequest) {
            if ($isTokenValid) {
                return new Response(status: Response::HTTP_CREATED);
            }
            return new Response(status: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->redirectToRoute(
            route: "{$this->route}_index",
            status: Response::HTTP_SEE_OTHER
        );
    }
}
