<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData(); // Récupère les données du formulaire

            $entityManager->persist($message);
            $entityManager->flush();

            // Création du mail
            $email = (new TemplatedEmail())
                ->from('no-reply@example.com') 
                ->to(new Address('admin@example.com'))
                ->subject('Nouveau message de contact')
                ->htmlTemplate('emails/contact_email.html.twig') 
                ->context([
                    'user_email' => $message->getEmail(),
                    'subject' => $message->getObjet(),
                    'message' => $message->getMessage(),
                ]);

            // Envoi du mail
            $mailer->send($email);

            // Ajout d'un message flash pour informer l'utilisateur
            $this->addFlash('success', 'Votre message a bien été envoyé!');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
