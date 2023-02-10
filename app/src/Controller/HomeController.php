<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Livre;
use App\Form\ContactType;
use App\Form\LivreType;
use App\Repository\ContactRepository;
use App\Repository\LivreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    private string $titre = "un titre";

    #[Route('/', name: 'index')]
    public function index(LivreRepository $livreRepository)
    {
        //dump($livreRepository->findAll());
        return $this->render('front/index.html.twig',[
            'livres' => $livreRepository->findAllOptimise(),
          // 'nombreLivre' => $livreRepository->count([])
           // 'nombreLivre' => count($livreRepository->findAll())
        ]);
    }

    #[Route('/filter/{filtering}', name: "filter", methods: ["GET"])]
    public function filter(string $filtering, LivreRepository $livreRepository)
    {
        $livres = [];
        switch ($filtering){
            case 'all':
                $livres = $livreRepository->findAllOptimise();
                break;
            case 'down':
                $livres = $livreRepository->findOptimiseDownOrUp();
                break;
            case 'up':
                $livres = $livreRepository->findOptimiseDownOrUp('ASC');
                break;
            case 'lastfive':
                $livres = $livreRepository->findOptimiseLastFive();
                break;

        }

        return $this->json(
            // décla du tableau qui sera jsonencoder
            [
                'html' => $this->renderView('front/_listLivre.html.twig', ['livres' => $livres])
            ]
        );

//        return $this->render("front/index.html.twig",[
//            'livres' => $livres
//        ]);
    }



    #[Route('/home', name: "accueil", methods: ['GET'])]
    public function bienvenue()
    {
        $titre = "Bienvenue sur Symfony";

        return $this->render(
            "front/home.html.twig",[
                'titre' => $titre
            ]
        );
    }

    #[Route('/page/{numero}', name: 'page',
        requirements: ['numero' => '\d+'],
        methods: ['GET', 'POST'],
        condition: "params['numero'] < 20")]
    public function page(string $numero) : Response
    {
        return $this->render("front/page.html.twig", [
            "titre" => $this->titre,
                'numero' => $numero
            ]

        );

    }

    #[Route("/listArticle", name: 'articles', methods: ["GET"])]
    public function listArticles()
    {
        $articles = [
            ['titre'=>'titre article 1', 'resume'=> "Ceci est le résumé de l'artice 1"],
            ['titre'=>'titre article 2', 'resume'=> "Ceci est le résumé de l'artice 2"],
            ['titre'=>'titre article 3', 'resume'=> "Ceci est le résumé de l'artice 3"],
        ];

        $livre = new Livre();



        return $this->render("front/_listArticle.html.twig", [
            'articles' => $articles,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, ManagerRegistry $manager) 
    {
        // $formulaire = $this->createFormBuilder() // Construction du formulaire
        // ->add('nom', TextType::class)
        // ->add('email', EmailType::class)
        // ->add('message', TextAreaType::class)
        // ->add('Envoyer', SubmitType::class)
        // ->getForm();

        $formulaire = $this->createForm(ContactType::class); // Création du formulaire
        $formulaire->handleRequest($request); // Écoute du formulaire

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            // dump($formulaire->getData());
            $entityManager = $manager->getManager();
            $entityManager->persist($formulaire->getData());
            $entityManager->flush();
            return $this->redirectToRoute('msg');
        }

        return $this->render('front/_formulaire.html.twig', [
            'form' => $formulaire,
        ]);
    }

    #[Route('/msgList', name: 'msg')]
    public function messageList(ContactRepository $contactRepo)
    {
        return $this->render('front/messageList.html.twig', [
            'message' => $contactRepo->findAll(),
        ]);
    }

    #[Route('/livreForm', name: 'addLivre')]
    public function livreForm(Request $request, ManagerRegistry $managerRegistry)
    {
        $lForm = $this->createForm(LivreType::class);

        $lForm->handleRequest($request);

        if ($lForm->isSubmitted() && $lForm->isValid()) {
            $em = $managerRegistry->getManager();
            $em->persist($lForm->getData());
            $em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('front/forms/livreForm.html.twig', [
            'lForm' => $lForm
        ]);
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError(); // On retourne l'erreur d'anthentification
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/forms/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }
}