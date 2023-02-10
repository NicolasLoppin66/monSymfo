<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/bienvenue', name: 'adminIndex', methods: ['GET'])]
    public function homeAdmin()
    {
        return $this->render('admin/home.html.twig');
    }
}