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
    #[Route('/messages', name: 'app_message_index')] 
    public function index(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll(); 

        return $this->render('message/index.html.twig', [
            'messages' => $messages, 
        ]);
    }

    #[Route('/messages/{id}', name: 'app_message_show', requirements: ['id' => '\d+'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message, 
        ]);
    }

    #[Route('/messages/{id}/edit', name: 'app_message_edit')] 
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message); 
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); 
            return $this->redirectToRoute('app_message_index'); 
        }

        return $this->render('message/edit.html.twig', [
            'form' => $form->createView(), 
            'message' => $message, 
        ]);
    }

    #[Route('/messages/new', name: 'app_message_new')] 
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message(); 
        $form = $this->createForm(MessageType::class, $message); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message); 
            $entityManager->flush(); 
            return $this->redirectToRoute('app_message_index'); 
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    #[Route('/messages/{id}/delete', name: 'app_message_delete', methods: ['POST'])] 
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message); 
            $entityManager->flush(); 
        }

        return $this->redirectToRoute('app_message_index'); 
    }

}
