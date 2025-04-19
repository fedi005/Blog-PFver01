<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        // Créer le formulaire directement dans le contrôleur
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, ['label' => 'Votre nom'])
            ->add('email', EmailType::class, ['label' => 'Votre email'])
            ->add('sujet', TextType::class, ['label' => 'Sujet'])  // Ajout du champ 'sujet'
            ->add('message', TextareaType::class, ['label' => 'Votre message'])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement des données du formulaire (envoi d'email, etc.)
            // Pour l'instant, affichons juste un message de succès
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            // Rediriger vers la même page après soumission
            return $this->redirectToRoute('app_contact');
        }

        // Passer le formulaire au template
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
