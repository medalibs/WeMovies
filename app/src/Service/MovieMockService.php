<?php

namespace App\Service;

use App\ValueObject\Genre;
use App\ValueObject\Movie;

class MovieMockService implements MovieServiceInterface
{
    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return [
            new Genre(1, 'Movie 1'),
            new Genre(2, 'Movie 2'),
        ];
    }

    /**
     * @param int[] $genres
     *
     * @return array{all: Movie[],bestMovie: Movie}
     */
    public function getFilmsByGenre(array $genres): array
    {
        return [
            'all' => [
                new Movie(
                    'title 1',
                    'Description 1',
                    'http://www.img.com/1.jpg',
                    2016,
                    100,
                    3,
                    'http://www.img.com/b_1.jpg',
                ),
            ],
            'bestMovie' => new Movie(
                'title best',
                'Description best',
                'http://www.img.com/10.jpg',
                2019,
                300,
                5,
                'http://www.img.com/b_10.jpg',
            ),
        ];
    }
}
