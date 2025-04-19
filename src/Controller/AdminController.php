<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType; // Le formulaire pour ajouter/éditer un article
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AdminController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(AuthorizationCheckerInterface $authChecker): Response
    {
        // Vérifie si l'utilisateur est admin
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home'); // Redirige si l'utilisateur n'est pas admin
        }

        // Récupère tous les articles
        $articles = $this->articleRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/admin/article/new', name: 'article_new')]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article ajouté avec succès!');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/article/{id}/edit', name: 'article_edit')]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Article modifié avec succès!');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/admin/article/{id}/delete', name: 'article_delete')]
    public function delete(Article $article): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article supprimé avec succès!');

        return $this->redirectToRoute('app_admin');
    }
}
