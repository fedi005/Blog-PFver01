<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/messages', name: 'app_message_index')] // Affiche tous les messages
    public function index(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll(); // Récupère tous les messages

        return $this->render('message/index.html.twig', [
            'messages' => $messages, // Passe les messages à la vue
        ]);
    }

    #[Route('/messages/{id}', name: 'app_message_show')] // Affiche un message spécifique
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message, // Passe le message à la vue
        ]);
    }

    #[Route('/messages/{id}/edit', name: 'app_message_edit')] // Édite un message spécifique
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message); // Crée le formulaire pour éditer le message
        $form->handleRequest($request); // Gère les données envoyées

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Sauvegarde les modifications
            return $this->redirectToRoute('app_message_index'); // Redirige vers la liste des messages
        }

        return $this->render('message/edit.html.twig', [
            'form' => $form->createView(), // Passe le formulaire à la vue
            'message' => $message, // Passe le message à la vue pour affichage
        ]);
    }

    #[Route('/messages/new', name: 'app_message_new')] // Crée un nouveau message
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message(); // Crée une nouvelle instance de Message
        $form = $this->createForm(MessageType::class, $message); // Crée le formulaire pour un nouveau message
        $form->handleRequest($request); // Gère les données envoyées

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message); // Persiste le nouveau message en base de données
            $entityManager->flush(); // Sauvegarde le message
            return $this->redirectToRoute('app_message_index'); // Redirige vers la liste des messages
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView(), // Passe le formulaire à la vue
        ]);
    }
}
