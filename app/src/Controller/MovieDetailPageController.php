<?php

namespace App\Controller;

use App\Service\MovieServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MovieDetailPageController extends AbstractController
{
    #[Route('/movie-detail/{id}', name: 'app_modal_movie_fragment')]
    public function movieDetail(int $id, MovieServiceInterface $movieService): Response
    {
        return $this->render('pages/movie.html.twig', [
            'movie' => $movieService->getMovieById($id),
            'trailer' => $movieService->getMovieTrailerById($id),
        ]);
    }
}
