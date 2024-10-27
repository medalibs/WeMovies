<?php

namespace App\Controller;

use App\Service\MovieServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(MovieServiceInterface $movieService): Response
    {
        return $this->render('pages/home.html.twig', [
            'genres' => $movieService->getGenres(),
            'movies' => $movieService->getMoviesByGenre([28]),
        ]);
    }
}
