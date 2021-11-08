<?php

declare(strict_types=1);

namespace App\Controller\Student;

use App\DataTransfert\ContactRequestData;
use App\Entity\User;
use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User getUser()
 */
class ContactController extends AbstractController
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/student/contact', name: 'student_contact_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        // on récupère l'utilisateur connecté pour avoir accès à son adresse email
        $user = $this->getUser();

        // on utilise un object pure au lieu d'une entité parce qu'on ne sauvegarde rien en base de donnée
        $data = new ContactRequestData();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // on envoie le mail à l'administrateur
                // de la part de l'utilisateur connecté qui effectue l'action
                $this->mailer->send(
                    message: (new Email())
                        ->subject($data->subject)

                        // l'adresse email de l'administrateur est défini dans les variables d'env pour faciliter sa mise à jour
                        ->to(new Address($_ENV['APP_ADMIN_MAIL'], 'Administrateur'))
                        ->from(new Address($user->getEmail(), $user->getName()))
                        ->text($data->message)
                );

                return $this->redirectToRoute('student_index');

            } catch (\Exception $e) {
                // on enregistre l'erreur dans les logs et affiche un message personnalisé à l'utilisateur
                $this->logger->error($e->getMessage(), $e->getTrace());
                $this->addFlash('error', "Désolé une erreur est survenue, veuillez réessayé plus tard !");
            }
        }

        return $this->render('student/contact.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
