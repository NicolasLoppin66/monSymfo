<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private $repoLivre;
    private $repoAuteur;
    private $repoCategorie;

    public function __construct(
        LivreRepository $livreRepository,
        AuteurRepository $auteurRepository,
        CategorieRepository $categorieRepository
    )
    {
        $this->repoLivre = $livreRepository;
        $this->repoAuteur = $auteurRepository;
        $this->repoCategorie = $categorieRepository;
    }

    #[Route('/bienvenue', name: 'adminIndex', methods: ['GET'])]
    public function homeAdmin()
    {
        return $this->render('admin/home.html.twig');
    }

    #[Route('/adminPage/{link}', name: 'adminPage', methods: ['GET'])]
    public function adminPage($lien)
    {
        $template = 'admin/' . $lien . '.html.twig';
        $params = [];
        $method = 'repo' . ucfirst($lien);

        $params[$lien] = ($lien == 'livres') ? ($this->$method->findAllOptimise()) : ($this->$method->findAll());

        // switch ($lien) {
        //     case 'livres':
        //         $params['livres'] = $this->repoLivre->findAllOptimise();
        //         break;
        //     case 'auteurs':
        //         $auteurs = $this->repoAuteur->findAllOptimise();
        //         break;
        //     case 'categories':
        //         $categories = $this->repoCategorie->findAllOptimise();
        //         break;
        // }

        return $this->render($template, $params);
    }
}