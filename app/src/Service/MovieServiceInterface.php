<?php

namespace App\Service;

use App\ValueObject\Genre;
use App\ValueObject\Movie;

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
    public function getFilmsByGenre(array $genres): array;
}
