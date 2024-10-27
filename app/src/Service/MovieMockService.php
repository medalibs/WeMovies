<?php

namespace App\Service;

use App\ValueObject\Genre;
use App\ValueObject\Movie;
use App\ValueObject\Trailer;

class MovieMockService implements MovieServiceInterface
{
    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return [
            new Genre(1, 'Action'),
            new Genre(2, 'Comedy'),
        ];
    }

    /**
     * @param int[] $genres
     *
     * @return array{all: Movie[],bestMovie: Movie}
     */
    public function getMoviesByGenre(array $genres): array
    {
        return [
            'all' => [
                new Movie(
                    1,
                    'title 1',
                    'Description 1',
                    'https://www.img.com/1.jpg',
                    2016,
                    100,
                    3,
                    'https://www.img.com/b_1.jpg',
                ),
            ],
            'bestMovie' => new Movie(
                2,
                'title best',
                'Description best',
                'https://www.img.com/10.jpg',
                2019,
                300,
                5,
                'https://www.img.com/b_10.jpg',
            ),
        ];
    }

    public function getMovieById(int $id): Movie
    {
        return new Movie(
            2,
            'title best',
            'Description best',
            'https://www.img.com/10.jpg',
            2019,
            300,
            5,
            'https://www.img.com/b_10.jpg',
        );
    }

    public function getMovieTrailerById(int $id): Trailer
    {
        return new Trailer('name', 'https://test.com/video.mov');
    }
}
