<?php

namespace App\Service;

use App\ValueObject\Genre;
use App\ValueObject\Movie;
use App\ValueObject\Trailer;

interface MovieServiceInterface
{
    /**
     * @return Genre[]
     */
    public function getGenres(): array;

    /**
     * @param int[] $genres
     *
     * @return array{all: Movie[],bestMovie: Movie}
     */
    public function getMoviesByGenre(array $genres): array;

    public function getMovieById(int $id): ?Movie;

    public function getMovieTrailerById(int $id): ?Trailer;
}
