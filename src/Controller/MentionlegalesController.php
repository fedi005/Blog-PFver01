<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MentionlegalesController extends AbstractController
{
    #[Route('/mentionlegales', name: 'app_mentionlegales')]
    public function index(): Response
    {
        return $this->render('mentionlegales/index.html.twig', [
            'controller_name' => 'MentionlegalesController',
        ]);
    }
}
