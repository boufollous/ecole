<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User getUser()
 */
class ProfileController extends AbstractController
{

    #[Route('/student/profile', name: 'student_profile_index', methods: ['GET'])]
    public function index(): Response
    {
        $item = $this->getUser();
        return $this->render('student/profile/index.html.twig', compact('item'));
    }

    #[Route('/student/profile/edit', name: 'student_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        // on sépare les formulaires en deux pour vérifier le mot de passe avant le changement
        $updateProfileForm = $this->createForm(UserType::class, $user);
        $updatePasswordForm = $this->createForm(UpdatePasswordType::class, $user);

        // on traite les données du profile
        $updateProfileForm->handleRequest($request);
        if ($updateProfileForm->isSubmitted() && $updateProfileForm->isValid()) {
            $em->persist($user);
            $em->flush();
        }

        // on traite le changement du mot de passe en vérifiant que l'ancien mot de passe et valide
        $updatePasswordForm->handleRequest($request);
        if ($updatePasswordForm->isSubmitted() && $updatePasswordForm->isValid()) {
            if ($hasher->isPasswordValid($user, $updatePasswordForm->get('password')->getData())) {

                // on hash le nouveau mot de passe pour mettre les futurs connexions
                $password = $updatePasswordForm->get('plainPassword')->getData();
                $user->setPassword($hasher->hashPassword($user, $password));

                $em->persist($user);
                $em->flush();
                $this->addFlash("success", "Information de profile mise à jour !");
            } else {
                $updatePasswordForm->addError(new FormError("Mot de passe actuel invalide !"));
            }
        }

        return $this->render('student/profile/edit.html.twig', [
            'updateProfileForm' => $updateProfileForm->createView(),
            'updatePasswordForm' => $updatePasswordForm->createView()
        ]);
    }
}
