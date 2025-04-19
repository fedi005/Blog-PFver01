<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PolitiquedeconfidentialiteController extends AbstractController
{
    #[Route('/politiquedeconfidentialite', name: 'app_politiquedeconfidentialite')]
    public function index(): Response
    {
        return $this->render('politiquedeconfidentialite/index.html.twig', [
            'controller_name' => 'PolitiquedeconfidentialiteController',
        ]);
    }
}
