<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransfert\RegistrationRequestData;
use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route(path="/registration", name="auth_registration", methods={"GET", "POST"})
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {
        $data = new RegistrationRequestData();
        $form = $this->createForm(RegistrationType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // création du compte et hasher du mot de passe
            $user = User::createFromRegistrationRequest($data);
            $user->setPassword(
                password: $hasher->hashPassword(
                    user: $user,
                    plainPassword: $data->password
                )
            );

            // persister en base de donnée
            $em->persist($user);
            $em->flush();

            // affichage du message de succès
            $this->addFlash('success', 'Merci votre inscription est maintenant en attente de confirmation');
            return $this->redirectToRoute('auth_login');
        }

        return $this->render(
            'security/registration.html.twig',
            ['form' => $form->createView()]
        );
    }
}
