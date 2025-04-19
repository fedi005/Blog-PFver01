<?php
namespace App\Controller;

use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // Créer le formulaire directement dans le contrôleur
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, ['label' => 'Votre nom'])
            ->add('email', EmailType::class, ['label' => 'Votre email'])
            ->add('sujet', TextType::class, ['label' => 'Sujet'])
            ->add('message', TextareaType::class, ['label' => 'Votre message'])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données soumises dans le formulaire
            $data = $form->getData();

            // Créer une nouvelle instance de Message et lui attribuer les valeurs du formulaire
            $message = new Message();
            $message->setNom($data['nom']);
            $message->setEmail($data['email']);
            $message->setSujet($data['sujet']);
            $message->setContenu($data['message']);

            // Persister le message dans la base de données
            $entityManager->persist($message);
            $entityManager->flush();

            // Création de l'email
            $email = (new Email())
                ->from($data['email'])  // L'email de l'utilisateur
                ->to('fedikhaldi88@gmail.com')
                ->subject('Nouveau message de contact: ' . $data['sujet'])
                ->text('Nom: ' . $data['nom'] . "\n" .
                       'Email: ' . $data['email'] . "\n" .
                       'Message: ' . $data['message']); // Corps du message

            // Envoi de l'email
            $mailer->send($email);

            // Afficher un message de succès
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
