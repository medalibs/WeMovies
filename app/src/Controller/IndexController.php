<?php

namespace App\Controller;

use App\Service\MovieServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(MovieServiceInterface $movieService): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'genres' => $movieService->getGenres(),
            'movies' => $movieService->getFilmsByGenre([28]),
        ]);
    }
}
